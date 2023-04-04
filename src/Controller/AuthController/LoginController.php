<?php

namespace App\Controller\AuthController;

use App\Repository\UserRepository;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[OA\Tag(name: 'Auth')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'username',
                    type: 'string',
                    example: 'test@gmail.com'
                ),
                new OA\Property(
                    property: 'password',
                    type: 'string',
                    example: 'test'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns a JWT token',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'token',
                    type: 'string',
                    example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NzYzMjM1MzQsImV4....'
                ),
            ]
        )
    )]
    #[Route('/api/login', name: 'app_login', methods: ['POST'])]
    public function userLogin(
        Request $request,
        UserRepository $userRepository
    ) {
    }

    #[OA\Tag(name: 'Auth')]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'refresh_token',
                    type: 'string',
                    example: 'xxx00a7a9e970f9bbe076e05743e00648908c38366c551a8cdf524ba424fc3e520988f6320a549e5'
                ),
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns a JWT token',
        content: new OA\JsonContent(
            type: 'object',
            properties: [
                new OA\Property(
                    property: 'token',
                    type: 'string',
                    example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2NzYzMjM1MzQsImV4....'
                ),
            ]
        )
    )]
    #[Route('/api/token/refresh', name: 'app_refresh_token', methods: ['POST'])]
    public function refreshToken(Request $request)
    {
    }
}
