<?php

namespace App\Security;

use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class JwtFailureHandler implements AuthenticationFailureHandlerInterface
{
    private $jwtEncoder;
    private $userRepository;

    public function __construct(JWTEncoderInterface $jwtEncoder, UserRepository $userRepository)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->userRepository = $userRepository;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->userRepository->findOneBy(['email' => $data['username']]);
        if (!$user) {
            throw new AuthenticationException('Email or password is incorrect');
        }
        if (!password_verify($data['password'], $user->getPassword())) {
            throw new AuthenticationException('Email or password is incorrect');
        }

        if (!$user->isIsActive()) {
            throw new AuthenticationException('Your account is not active');
        }

        if (!$user->isVerified()) {
            throw new AuthenticationException('Your account is not verified');
        }
        // Génération du token JWT
        $payload = [
            'username' => $user->getUsername(),
            // Ajoutez toutes les données que vous souhaitez inclure dans le payload ici
        ];
        $jwt = $this->jwtEncoder->encode($payload);

        // Renvoi du token JWT
        return new Response($jwt, Response::HTTP_OK);
    }
}
