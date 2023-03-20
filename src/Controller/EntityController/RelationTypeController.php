<?php

namespace App\Controller\EntityController;

use App\Entity\RelationType;
use App\Repository\RelationTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RelationTypeController extends AbstractController
{
    /**
     * Return all relations types.
     */
    #[Route('/api/relationtype', name: 'relation_type', methods: ['GET'])]
    #[OA\Tag(name: 'Relation Type')]
    #[OA\Response(response: 200, description: 'Return all relations types')]
    public function getAllRelationTypes(RelationTypeRepository $relationTypeRepository, SerializerInterface $serializer): JsonResponse
    {
        $relationTypes = $relationTypeRepository->findAll();
        $jsonRelationTypes = $serializer->serialize($relationTypes, 'json', ['groups' => 'getRelationType']);

        return new JsonResponse($jsonRelationTypes, Response::HTTP_OK, [], true);
    }

    /**
     *This function allows us to get details of one relation type.
     */
    #[Route('/api/relationtype/{id}', name: 'relation_type_details', methods: ['GET'])]
    #[OA\Tag(name: 'Relation Type')]
    #[OA\Response(response: 200, description: 'Return details of one relation type')]
    #[OA\Parameter(name: 'id', description: 'The id of the relation type', in: 'path', required: true, example: 1)]
    public function getOneRelationsTypesDetails(RelationType $relationType, SerializerInterface $serializer): JsonResponse
    {
        $jsonRelationType = $serializer->serialize($relationType, 'json', ['groups' => 'getRelationTypesDetails']);

        return new JsonResponse($jsonRelationType, Response::HTTP_OK, [], true);
    }

    /**
     * This function allows us to create a relation type.
     */
    #[Route('/api/relationtype', name: 'create_relation_type', methods: ['POST'])]
    #[OA\Tag(name: 'Relation Type')]
    #[OA\Response(response: 201, description: 'Relation type is created')]
    #[OA\RequestBody(description: 'Create a relation type', required: true, attachables: [
        new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Relation type name'),
                ]
            )
        ),
    ])]
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $relationtype = $serializer->deserialize($request->getContent(), RelationType::class, 'json');

        $entityManager->persist($relationtype);
        $entityManager->flush();

        $location = $urlGenerator->generate('relation_type_details', ['id' => $relationtype->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        $jsonRelationType = $serializer->serialize($relationtype, 'json', ['groups' => 'getRelationTypesDetails']);

        return new JsonResponse($jsonRelationType, Response::HTTP_CREATED, ['location' => $location], true);
    }

    /**
     * This function allows us to update a relation type.
     */
    #[Route('/api/relationtype/{id}', name: 'update_relation_type', methods: ['PUT'])]
    #[OA\Tag(name: 'Relation Type')]
    #[OA\Parameter(name: 'id', description: 'The id of the relation type', in: 'path', required: true, example: 1)]
    #[OA\RequestBody(description: 'Update a relation type', required: true, attachables: [
        new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'Relation type name'),
                ]
            )
        ),
    ])]
    #[OA\Response(response: 200, description: 'Relation type is updated')]
    public function update(RelationType $relationType, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $content = $request->toArray();
        $relationType->setLabel($content['name']);

        $entityManager->persist($relationType);
        $entityManager->flush();

        $jsonRelationType = $serializer->serialize($relationType, 'json', ['groups' => 'getRelationType']);

        return new JsonResponse($jsonRelationType, Response::HTTP_OK, [], true);
    }

    /**
     * This function allows us to delete a relation type.
     *
     * @return JsonResponse
     */
    #[Route('/api/relationtype/{id}', name: 'delete_relation_type', methods: ['DELETE'])]
    #[OA\Parameter(name: 'id', description: 'The id of the relation type', in: 'path', required: true, example: 1)]
    #[OA\Response(response: 204, description: 'Relation type has been deleted')]
    #[OA\Tag(name: 'Relation Type')]
    public function delete(RelationType $relationType, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($relationType);
        $entityManager->flush();

        return new JsonResponse('Relation type has been deleted', Response::HTTP_NO_CONTENT, [], true);
    }
}
