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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
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


    /**
     * This function allows us to delete a user
     * @param User $user
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route("/api/users/{id}", name: "deleteUser", methods: ["DELETE"])]
    public function deleteUser(User $user, EntityManagerInterface $em) : JsonResponse
    {
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route("/api/users", name:"createUser", methods:["POST"])]
    public function createUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $em,
                               UrlGeneratorInterface $urlGenerator) : JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, "json");
        $em->persist($user);
        $em->flush();

        $jsonUser = $serializer->serialize($user,"json", ["groups"=>"getUsers"]);

        $location = $urlGenerator->generate("oneUser", ["id" => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route("/api/users/{id}", name:"updateUser", methods: ["PUT"])]
    public function updateUser(Request $request, SerializerInterface $serializer, User $user,
                               EntityManagerInterface $em) : JsonResponse
    {
        $updateUser = $serializer->deserialize($request->getContent(),
            User::class,
            "json",
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        $em->persist($updateUser);
        $em->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

}
