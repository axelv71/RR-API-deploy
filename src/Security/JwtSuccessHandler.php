<?php

namespace App\Security;


use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;

class JwtSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private $jwtEncoder;
    private $userRepository;
    private $refreshTokenGenerator;
    private $em;


    public function __construct(JWTEncoderInterface $jwtEncoder,
                                RefreshTokenGeneratorInterface $refreshTokenGenerator,
                                EntityManagerInterface $em,
                                UserRepository $userRepository)
    {
        $this->jwtEncoder = $jwtEncoder;
        $this->userRepository = $userRepository;
        $this->refreshTokenGenerator = $refreshTokenGenerator;
        $this->em = $em;


    }
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) : JsonResponse
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

        if (!$user->isVerified()){
            throw new AuthenticationException('Your account is not verified');
        }

        // Génération du token JWT
        $payload = [
            'username' => $user->getUsername(),
            // Ajoutez toutes les données que vous souhaitez inclure dans le payload ici
        ];

        $jwt = $this->jwtEncoder->encode($payload);
        $refresh_token = $this->refreshTokenGenerator->createForUserWithTtl($user, 2592000);
        $refresh_token->setUsername($user->getUsername());
        $refresh_token->setValid(\DateTime::createFromFormat('U', time() + 2592000));
        $this->em->persist($refresh_token);
        $this->em->flush();

        $data = [
            'token' => $jwt,
            'refresh_token' => $refresh_token->getRefreshToken()
        ];

        $json_data = json_encode($data);

        // Renvoi du token JWT
        return new JsonResponse($json_data, Response::HTTP_OK, [], true);
    }


}
