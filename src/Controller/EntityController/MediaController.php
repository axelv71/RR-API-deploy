<?php

namespace App\Controller\EntityController;

use App\Entity\Media;
use App\Entity\Ressource;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MediaController extends AbstractController
{
    /**
     * Add media to a resource
     *
     * @param Ressource $ressource
     * @param Request $request
     * @param SerializerInterface $serializer
     * @param EntityManagerInterface $entityManager
     * @param UrlGeneratorInterface $urlGenerator
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    #[Route('/api/{id}/upload', name: 'upload_media', methods: ['POST'])]
    #[OA\Tag('Media')]
    #[OA\Parameter(name: "id", description: "The id of the resource", in: "path", required: true, example: 1)]
    #[OA\Response(response: 201, description: 'Media has been uploaded successfully')]
    #[OA\Response(response: 400, description: 'Bad request')]
    #[OA\Response(response: 404, description: 'Resource not found')]
    #[OA\RequestBody(description: 'Media to upload', required: true, attachables: [
        new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(
                        property: 'file',
                        type: 'string',
                        format: 'binary',
                        description: 'The file to upload. File accepted: jpeg, png, gif, pdf, mp4, quicktime. (max 100Mo)'
                    )
                ]
            )
        )
    ])]
    public function upload(Ressource $ressource, Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse
    {
        // Get the file from the request and create a new Media object
        $media = new Media();
        $media->setRessource($ressource);
        $media->setFile($request->files->get('file'));
        $media->setTitle(explode('.', $request->files->get('file')->getClientOriginalName())[0]);
        $media->setMimetype($request->files->get('file')->getMimeType());
        $media->setUpdatedAt(new \DateTimeImmutable());

        // Validate the media
        $errors = $validator->validate($media);
        if (count($errors) > 0) {
            $jsonErrors = $serializer->serialize($errors, 'json');
            return new JsonResponse($jsonErrors, Response::HTTP_BAD_REQUEST, [], true);
        }

        // Save the media
        $entityManager->persist($media);
        $entityManager->flush();

        // Return the media
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
