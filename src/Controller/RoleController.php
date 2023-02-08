<?php

namespace App\Controller;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

class RoleController extends AbstractController
{

    /**
     * Get all roles
     *
     * @param RoleRepository $roleRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/roles', name: 'roles_getall', methods: ['GET'])]
    #[OA\Tag(name: "Roles")]
    #[OA\Response(response: 200, description: "Return all roles")]
    public function getAllRoles(RoleRepository $roleRepository, SerializerInterface $serializer): JsonResponse
    {
        $roles = $roleRepository->findAll();
        $jsonRoles = $serializer->serialize($roles, 'json', ['groups' => 'getRoles']);

        return new JsonResponse($jsonRoles, Response::HTTP_OK, [], true);
    }

    /**
     *  Get role by id
     *
     * @param Role $role
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/roles/{id}', name: 'roles_details', methods: ['GET'])]
    #[OA\Tag(name: "Roles")]
    #[OA\Parameter(name: "id", description: "The id of the relation type", in: "path", required: true, example: 1)]
    #[OA\Response(response: 200, description: "Return one role")]
    public function getOneRole(Role $role, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($role, 'json', ['groups' => 'getRoles']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     *  Create role
     *
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param RoleRepository $roleRepository
     * @param UrlGeneratorInterface $urlGenerator
     * @return JsonResponse
     */
    #[Route('/api/roles', name: 'roles_create', methods: ['POST'])]
    #[OA\Tag(name: "Roles")]
    #[OA\RequestBody(description: "Create a role", required: true, attachables: [
        new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Role name"),
                ]
            )
        )
    ])]
    #[OA\Response(response: 200, description: "Return one role")]
    #[OA\Response(response: 409, description: "Role already exist")]
    public function createRole(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, RoleRepository $roleRepository, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $role = $serializer->deserialize($request->getContent(), Role::class, 'json');
        $role->setName(self::format($role->getName()));

        $testRole = $roleRepository->findBy(['name' => $role->getName()]);
        if (count($testRole) != 0) {
            return new JsonResponse("Role already exist", Response::HTTP_CONFLICT, [], true);
        }

        $entityManager->persist($role);
        $entityManager->flush();


        $location = $urlGenerator->generate('relation_type_details', ['id' => $role->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $jsonRole = $serializer->serialize($role, 'json', ['groups' => 'getRoles']);

        return new JsonResponse($jsonRole, Response::HTTP_CREATED, ['location' => $location], true);
    }

    #[Route('/api/roles/{id}', name: 'roles_update', methods: ['PUT'])]
    #[OA\Tag(name: "Roles")]
    #[OA\Parameter(name: "id", description: "The id of the relation type", in: "path", required: true, example: 1)]
    #[OA\RequestBody(description: "Edit role", required: true, attachables: [
        new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Role name"),
                ]
            )
        )
    ])]
    #[OA\Response(response: 200, description: "Return one role")]
    #[OA\Response(response: 409, description: "Role already exist")]
    public function updateRole(Role $role, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, RoleRepository $roleRepository, UrlGeneratorInterface $urlGenerator)
    {
        $content = $request->toArray();
        $role->setName(self::format($content['name']));

        $testRole = $roleRepository->findBy(['name' => $role->getName()]);
        if (count($testRole) != 0) {
            return new JsonResponse("Role already exist", Response::HTTP_CONFLICT, [], true);
        }

        $entityManager->persist($role);
        $entityManager->flush();


        //$location = $urlGenerator->generate('relation_type_details', ['id' => $role->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $jsonRole = $serializer->serialize($role, 'json', ['groups' => 'getRoles']);

        return new JsonResponse($jsonRole, Response::HTTP_OK, [], true);
    }

    #[Route('/api/roles/{id}', name: 'roles_delete', methods: ['DELETE'])]
    #[OA\Tag(name: "Roles")]
    #[OA\Parameter(name: "id", description: "The id of the relation type", in: "path", required: true, example: 1)]
    #[OA\Response(response: 204, description: "Role has been deleted")]
    public function deleteRole(Role $role, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($role);
        $entityManager->flush();

        return new JsonResponse("Role has been deleted", Response::HTTP_NO_CONTENT, [], true);
    }

    private function format(string $name): string {
        return str_replace(" ", "_", strtoupper($name));
    }
}
