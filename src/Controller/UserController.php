<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;


use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;
use OpenApi\Attributes as OA;

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
    #[OA\Tag(name: "User")]
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
    #[OA\Response(response: 200, description: "Returns one user", content: new Model(type: User::class))]
    #[OA\Tag(name: "User")]
    #[OA\Parameter(name: "id", description: "The id of the user", in: "path", required: true, example: 1)]
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
    #[OA\Response(response: 204, description: "Delete a user")]
    #[OA\Tag(name: "User")]
    #[OA\Parameter(name: "id", description: "The id of the user", in: "path", required: true, example: 1)]
    public function deleteUser(User $user, EntityManagerInterface $em) : JsonResponse
    {
        $em->remove($user);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
