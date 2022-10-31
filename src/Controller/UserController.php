<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;

class UserController extends AbstractController
{

    /**
     * This function allows us to get all users
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/users', name: 'users', methods:["GET"])]
    public function getAllUsers(UserRepository $userRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $userList = $userRepository->findAll();
        $jsonUserList = $serializer->serialize($userList, "json", ["groups"=>"getUsers"]);
        return new JsonResponse($jsonUserList, Response::HTTP_OK , [], true);
    }


    /**
     * This function allows us to get one user by his id
     * @param User $user
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route("/api/users/{id}", name: "oneUser", methods: ["GET"])]
    public function getOneUser(User $user, SerializerInterface $serializer) : JsonResponse
    {
        $jsonUser = $serializer->serialize($user, "json", ["groups" => "getUsers"]);
        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }


    #[Route("/api/users/{id}", name: "deleteUser", methods: ["DELETE"])]
    public function deleteUser(User $user, EntityManagerInterface $em) : JsonResponse
    {
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

}
