<?php

namespace App\Controller;

use App\Entity\Settings;
use App\Entity\User;
use App\Repository\SettingsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class SettingsController extends AbstractController
{
    /**
     * This function allows us to get all settings of a user
     * @param User $user
     * @param SettingsRepository $settingsRepository
     * @return Response
     */
    #[Route("/api/settings/user/{id}", name: "userSettings", methods: ["GET"])]
    #[OA\Response(response: 200, description: "Returns user settings")]
    #[OA\Tag(name: "Settings")]
    #[OA\Parameter(name: "id", description: "The id of the user", in: "path", required: true, example: 1)]
    public function getUserSettings(User $user, SettingsRepository $settingsRepository): JsonResponse
    {
        // Get user settings
        $settings = $settingsRepository->findOneBy(["user" => $user]);

        return $this->json($settings, Response::HTTP_OK, [], ["groups" => "getSettings"]);
    }

    /**
     * This function allows us to get settings by id
     * @param Settings $settings
     * @return Response
     */
    #[Route("/api/settings/{id}", name: "settings", methods: ["GET"])]
    #[OA\Response(response: 200, description: "Returns settings")]
    #[OA\Tag(name: "Settings")]
    #[OA\Parameter(name: "id", description: "The id of the settings", in: "path", required: true, example: 1)]
    public function getOneSettings(Settings $settings): JsonResponse
    {
        return $this->json($settings, Response::HTTP_OK, [], ["groups" => "getSettings"]);
    }

    /**
     * This function allows us to update settings
     * @param Settings $settings
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return Response
     */
    #[Route("/api/settings/{id}", name: "updateSettings", methods: ["PUT"])]
    #[OA\Response(response: 200, description: "Returns updated settings")]
    #[OA\Tag(name: "Settings")]
    #[OA\Parameter(name: "id", description: "The id of the settings", in: "path", required: true, example: 1)]
    #[OA\RequestBody(required: true, attachables: [
        new OA\MediaType(
            mediaType: "application/json",
            schema: new OA\Schema(
                type: "object",
                properties: [
                    new OA\Property(property: "isDark", type: "boolean", example: "true"),
                ]
            )
        )
    ])]
    public function updateSettings(Settings $settings,  EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        $jsonContent = $request->getContent();

        $content = $request->toArray();
        $isDark = $content["isDark"] === 'true';

        $settings->setIsDark($isDark);

        $entityManager->persist($settings);
        $entityManager->flush();

        return new JsonResponse("Settings updated", Response::HTTP_OK);
    }
}
