<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Nelmio\ApiDocBundle\Annotation\Model;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use OpenApi\Attributes as OA;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;
    private $verifyEmailHelper;

    public function __construct(EmailVerifier $emailVerifier, VerifyEmailHelperInterface $helper)
    {
        $this->emailVerifier = $emailVerifier;
        $this->verifyEmailHelper = $helper;
    }


    #[OA\Tag(name: "Register")]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(
                    property: "email",
                    type: "string",
                    example: "test@gmail.com"
                ),
                new OA\Property(
                property: "password",
                type: "string",
                example: "test"
                ),
                new OA\Property(
                    property: "passwordConfirm",
                    type: "string",
                    example: "test"
                ),
                new OA\Property(
                    property: "name",
                    type: "string",
                    example: "test_name"
                ),
                new OA\Property(
                    property: "surname",
                    type: "string",
                    example: "test_surname"
                ),
                new OA\Property(
                    property: "pseudo",
                    type: "string",
                    example: "test_pseudo"
                ),

            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Returns the user created",
        content: new Model(type: User::class)
    )]
    #[Route('/api/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request,
                             UserRepository $userRepository,
                             UserPasswordHasherInterface $userPasswordHasher,
                             EntityManagerInterface $em): JsonResponse
    {
        $user = new User();
        $data = json_decode($request->getContent(), true);

        if (empty($data['email']) ||
            empty($data['password']) ||
            empty($data['passwordConfirm']) ||
            empty($data['name']) ||
            empty($data['surname']) ||
            empty($data['pseudo'])){
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        if ($data['password'] !== $data['passwordConfirm']) {
            throw new NotFoundHttpException('Passwords do not match!');
        }

        if ($userRepository->findOneBy(['email' => $data['email']])) {
            throw new NotFoundHttpException('Email already used!');
        }

        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setSurname($data['surname']);
        $user->setPseudo($data['pseudo']);
        $user->setIsActive(true);
        $user->setPassword($userPasswordHasher->hashPassword($user, $data['password']));
        $user->setRoles(['ROLE_USER', 'ROLE_USER_AUTHENTICATED']);
        //change this after enabled email verification
        $user->setIsVerified(true);
        $em->persist($user);
        $em->flush();

        /*$signatureComponents = $this->verifyEmailHelper->generateSignature(
            'app_verify_email',
            $user->getId(),
            $user->getEmail()
        );*/

        /*// generate a signed url and email it to the user
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('resourcesrelationnelles@gmail.com', '(Re)Sources Relationnelles'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
                ->context(['signedUrl' => $signatureComponents->getSignedUrl()])
        );
        */
        // do anything else you need here, like send an email
        $jsonData = [
            'message' => 'User created',
            'user' => $user
        ];

        $jsonData = json_encode($jsonData);
        return new JsonResponse($jsonData, Response::HTTP_CREATED, [], true);
    }


    /*#[Route('/api/register/verify', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }*/
}
