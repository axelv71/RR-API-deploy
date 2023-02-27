<?php

namespace App\Controller\EntityController;

use App\Entity\Media;
use App\Entity\Ressource;
use App\Repository\CategoryRepository;
use App\Repository\RelationRepository;
use App\Repository\RelationTypeRepository;
use App\Repository\RessourceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;
use Psr\Log\LoggerInterface;

class RessourceController extends AbstractController
{

    private LoggerInterface $logger;
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * This function allows us to get all ressources for public access
     * @param RessourceRepository $repository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/api/public/resources", name: "resources", methods: ["GET"])]
    #[OA\Tag(name: "Ressource")]
    #[OA\Parameter(name: "page", description: "Page number", in: "query", required: false, example: 1)]
    #[OA\Parameter(name: "pageSize", description: "Number of ressources per page", in: "query", required: false, example: 10)]
    public function getAllRessources(RessourceRepository $repository, SerializerInterface $serializer, Request $request) : JsonResponse
    {
        $ressourceList = $repository->getAllWithPagination($request->query->getInt('page', 1), $request->query->getInt('pageSize', 10));
        $jsonRessourceList = $serializer->serialize($ressourceList, "json", ["groups"=>"getRessources"]);
        return new JsonResponse($jsonRessourceList, Response::HTTP_OK, [], true);
    }

    /**
     * This function allows us to get all privates resources for user access from all type of relations
     * @param RessourceRepository $ressourceRepository
     * @param RelationRepository $relationRepository
     * @param RelationTypeRepository $relationTypeRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    #[OA\Tag(name: "Ressource")]
    #[OA\Parameter(name: "page", description: "Page number", in: "query", required: false, example: 1)]
    #[OA\Parameter(name: "pageSize", description: "Number of ressources per page", in: "query", required: false, example: 10)]
    #[OA\Parameter(name: "relation_type_id", description: "Relation type id (used to sort resources by relation type)", in: "query", required: false, example: 1)]
    #[Route("/api/resources", name: "user_resources", methods: ["GET"])]
    public function getAllRelationsRessources(RessourceRepository $ressourceRepository,
                                              RelationRepository $relationRepository,
                                              RelationTypeRepository $relationTypeRepository,
                                              SerializerInterface $serializer,
                                              Request $request) : JsonResponse
    {
        $user = $this->getUser();
        $user_id = $user->getId();

        $relation_type_id = $request->query->getInt('relation_type_id', 0);

        // Get all relations for user
        $relations = $relationRepository->retrieveAllRelationsByUser($user);

        $friends_relations = [];
        $friends_ids = [];

        // Get all friends ids to get their resources
        foreach ($relations as $relation){
            if ($relation->getRelationType()->getId() != $relation_type_id && $relation_type_id != 0){
                continue;
            }
            $sender_id = $relation->getSender()->getId();
            $receiver_id = $relation->getReceiver()->getId();
            if($sender_id == $user_id){
                $friend_id = $receiver_id;
            }else{
                $friend_id = $sender_id;
            }
            $friends_ids[] = $friend_id;
            $friends_relations[] = [
                $friend_id, $relation->getRelationType()->getId()
            ];
        }

        // Get all resources from friends
        $all_relations_resources = $ressourceRepository->getAllWithPaginationByRelations($friends_ids, $request->query->getInt('page', 1), $request->query->getInt('pageSize', 10));
        $all_relations_resources = $all_relations_resources->getQuery()->getResult();


        $friends_resources = [];
        foreach($all_relations_resources as $resource){
            $creator_id = $resource->getCreator()->getId();
            $relation_type_array = $resource->getRelationType();
            foreach($relation_type_array as $relation_type){
                $relation_type_id = $relation_type->getId();
                if(in_array([$creator_id, $relation_type_id], $friends_relations)){
                    $friends_resources[] = $resource;
                }
            }
        }

        $this->logger->info("Nombre de ressources : ".count($friends_resources));
        $jsonResourceList = $serializer->serialize($friends_resources, "json", ["groups"=>"getRessources"]);
        return new JsonResponse($jsonResourceList, Response::HTTP_OK, [], true);
    }



    #[OA\Tag(name: "Ressource")]
    #[OA\Response(response: 200, description: "Return user's ressources", content: new Model(type: Ressource::class))]
    #[Route('/api/resources/user_ressources', name: 'getRessourcesByUser', methods: ['GET'])]
    public function getUserRessources(SerializerInterface $serializer, RessourceRepository $repository) : JsonResponse
    {
        $user = $this->getUser();
        $ressources = $user->getRessources();
        $jsonRessources = $serializer->serialize($ressources, "json", ["groups"=>"getRessources"]);
        return new JsonResponse($jsonRessources, Response::HTTP_OK, [], true);
    }


    /**
     * This function allows us to get one ressource by his id
     * @param Ressource $ressource
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route("/api/resources/{id}", name: "oneRessource", methods: ["GET"])]
    #[OA\Tag(name: "Ressource")]
    #[OA\Response(response: 200, description: "Returns one ressource")]
    #[OA\Parameter(name: "id", description: "The id of the ressource", in: "path", required: true, example: 1)]
    public function getOneRessource(Ressource $ressource, SerializerInterface $serializer) : JsonResponse
    {
        $jsonRessource = $serializer->serialize($ressource, "json", ["groups"=>"getRessources"]);
        return new JsonResponse($jsonRessource, Response::HTTP_OK, [], true);
    }

    /**
     * This function allows us to delete a ressource
     * @param Ressource $ressource
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    #[Route("/api/resources/{id}", name: "deleteRessource", methods: ["DELETE"])]
    #[OA\Tag(name: "Ressource")]
    #[OA\Response(response: 204, description: "Delete one ressource")]
    #[OA\Parameter(name: "id", description: "The id of the ressource", in: "path", required: true, example: 1)]
    public function deleteRessource(Ressource $ressource, EntityManagerInterface $em) : JsonResponse
    {
        $em->remove($ressource);
        $em->flush();
        return new JsonResponse("Ressource deleted", Response::HTTP_NO_CONTENT, [], true);
    }

    /**
     * This function allows us to create a ressource
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface $urlGenerator
     * @param UserRepository $userRepository
     * @param CategoryRepository $categoryRepository
     * @return JsonResponse
     */
    #[Route("/api/resources", name: "addRessource", methods: ["POST"])]
    #[OA\Tag(name: "Ressource")]
    #[OA\Response(response: 201, description: "Create one ressource")]
    #[OA\RequestBody(description: "Create a ressource", required: true, attachables: [
        new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(property: "description", type: "string", example: "Ressource text content"),
                    new OA\Property(property: "category_id", type: "integer", example: 1),
                ]
            )
        )
    ])]
    public function addRessource(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, UserRepository $userRepository, CategoryRepository $categoryRepository) : JsonResponse
    {
        /* Creation de la ressource */
        $ressource = $serializer->deserialize($request->getContent(), Ressource::class, "json");

        $content = json_decode($request->getContent(), true);

        $user = $this->getUser();
        $categoryId = $content["category_id"];

        $category = $categoryRepository->find($categoryId);

        $ressource->setCreator($user);
        $ressource->setCategory($category);
        $ressource->setIsPublished(false);
        if ($user){
            $user->addRessource($ressource);
            $em->persist($user);
        }
        if ($category){
            $category->addRessource($ressource);
            $em->persist($category);
        }

        /* Upload */
        // TODO: Upload file at resource creation
        
        $em->persist($ressource);
        $em->flush();

        $jsonRessource = $serializer->serialize($ressource, "json", ["groups"=>"getRessources"]);

        $location = $urlGenerator->generate("oneRessource", ["id"=>$ressource->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonRessource, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    /**
     * This function allows us to update a ressource
     * @param Ressource $ressource
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route("/api/resources/{id}", name: "updateRessource", methods: ["PUT"])]
    #[OA\Tag(name: "Ressource")]
    #[OA\Response(response: 200, description: "Update one ressource")]
    #[OA\Parameter(name: "id", description: "The id of the ressource", in: "path", required: true, example: 1)]
    #[OA\RequestBody(description: "Update a ressource", required: true, attachables: [
        new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(property: "description", type: "string", example: "Ressource text content"),
                ]
            )
        )
    ])]
    public function updateRessource(Ressource $ressource, Request $request, SerializerInterface $serializer, EntityManagerInterface $em) : JsonResponse
    {
        $jsonRessource = $request->getContent();
        $updatedRessource = $serializer->deserialize($jsonRessource, Ressource::class, "json", [AbstractNormalizer::OBJECT_TO_POPULATE => $ressource]);
        $em->persist($updatedRessource);
        $em->flush();
        return new JsonResponse("Ressource updated", Response::HTTP_OK);
    }

}
