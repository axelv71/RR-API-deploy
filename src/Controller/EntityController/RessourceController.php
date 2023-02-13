<?php

namespace App\Controller\EntityController;

use App\Entity\Ressource;
use App\Repository\CategoryRepository;
use App\Repository\RessourceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RessourceController extends AbstractController
{
    /**
     * This function allows us to get all ressources
     * @param RessourceRepository $ressourceRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * @return JsonResponse
     */
    #[Route("/api/resources", name: "resources", methods: ["GET"])]
    #[OA\Tag(name: "Ressource")]
    public function getAllRessources(RessourceRepository $repository, SerializerInterface $serializer, Request $request) : JsonResponse
    {
        $ressourceList = $repository->findAll();
        $jsonRessourceList = $serializer->serialize($ressourceList, "json", ["groups"=>"getRessources"]);
        return new JsonResponse($jsonRessourceList, Response::HTTP_OK, [], true);
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
     * @param EntityManagerInterface $em
     * @param SerializerInterface $serializer
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
                    new OA\Property(property: "categoryid", type: "integer", example: 1),
                    new OA\Property(property: "creatorid", type: "integer", example: 1),
                ]
            )
        )
    ])]
    public function addRessource(Request $request,
                                 SerializerInterface $serializer,
                                 EntityManagerInterface $em,
                                 UrlGeneratorInterface $urlGenerator,
                                 UserRepository $userRepository,
                                 CategoryRepository $categoryRepository) : JsonResponse
    {

        $ressource = $serializer->deserialize($request->getContent(), Ressource::class, "json");

        $content = $request->toArray();

        $creatorId = $content["creatorid"];
        $categoryId = $content["categoryid"];

        $user = $userRepository->find($creatorId);
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
