<?php

namespace App\Controller;

use App\Entity\Media;
use App\Entity\Ressource;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use OpenApi\Attributes as OA;

class MediaController extends AbstractController
{
    /**
     * Add media to a resource
     *
     * @param Ressource $ressource
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('/api/{id}/upload', name: 'upload_media', methods: ['POST'])]
    #[OA\Tag('Media')]
    #[OA\Parameter(name: "id", description: "The id of the resource", in: "path", required: true, example: 1)]
    #[OA\Response(response: 201, description: 'Media has been uploaded successfully')]
    public function upload(Ressource $ressource, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $media = new Media();
        $request->files->get('file');
        $media->setRessource($ressource);
        $media->setFile($request->files->get('file'));
        $media->setUpdatedAt(new \DateTimeImmutable());

        $entityManager->persist($media);
        $entityManager->flush();

        $json = $serializer->serialize($media, 'json', ['groups' => 'getMedia']);
        $location = $urlGenerator->generate('detail_media', ['id' => $media->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($json, Response::HTTP_CREATED, ['location' => $location], true);
    }

    /**
     * Get all media
     *
     * @param MediaRepository $mediaRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/media', methods: ['GET'])]
    #[OA\Tag('Media')]
    public function all(MediaRepository $mediaRepository, SerializerInterface $serializer): JsonResponse
    {
        $medias = $mediaRepository->findAll();
        $json = $serializer->serialize($medias, 'json', ['groups' => 'getMedia']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * Get media by id
     *
     * @param Media $media
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/api/media/{id}', name: 'detail_media', methods: ['GET'])]
    #[OA\Tag('Media')]
    #[OA\Parameter(name: "id", description: "The id of the media", in: "path", required: true, example: 1)]
    #[OA\Response(response: 200, description: 'Return media')]
    public function getOne(Media $media, SerializerInterface $serializer): JsonResponse
    {
        $json = $serializer->serialize($media, 'json', ['groups' => 'getMedia']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * Delete a media
     *
     * @param Media $media
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    #[Route('/api/media/{id}', name: 'delete_media', methods: ['DELETE'])]
    #[OA\Tag('Media')]
    #[OA\Parameter(name: "id", description: "The id of the media", in: "path", required: true, example: 1)]
    #[OA\Response(response: 204, description: 'The media has been deleted successfully')]
    public function delete(Media $media, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($media);
        $entityManager->flush();

        return new JsonResponse("The media has been deleted successfully", Response::HTTP_NO_CONTENT, [], true);
    }
}
