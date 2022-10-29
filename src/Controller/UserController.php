<?php

namespace App\Controller;

use App\Repository\UserRepository;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('api/user', name: 'user', methods:["GET"])]
    public function getAllUsers(UserRepository $userRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $userList = $userRepository->findAll();
        $jsonUserList = $serializer->serialize($userList, "json");
        return new JsonResponse($jsonUserList, Response::HTTP_OK , [], true);
    }
}
