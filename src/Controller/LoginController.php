<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;

class LoginController extends AbstractController
{
    #[OA\Tag(name: "Auth")]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(
                    property: "username",
                    type: "string",
                    example: "test@gmail.com"
                ),
                new OA\Property(
                    property: "password",
                    type: "string",
                    example: "test"
                )
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Returns a JWT token",
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(
                    property: "token",
                    type: "string",
                    example: "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NzYzMjM1MzQsImV4...."
                )
            ]
        )
    )]
    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function userLogin(Request $request,
                              UserRepository $userRepository)
    {}
}
