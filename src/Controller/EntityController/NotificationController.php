<?php

namespace App\Controller\EntityController;

use App\Entity\User;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;
use Symfony\Component\Serializer\SerializerInterface;

class NotificationController extends AbstractController
{
    private LoggerInterface $logger;
    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * This function allows us to get all notifications of the user in the last 7 days
     * @param NotificationRepository $notificationRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[OA\Tag(name: "Notification")]
    #[OA\Response(
        response: 200,
        description: "Returns all notifications of the user in the last 7 days",
        content: new Model(type: Notification::class)
    )]
    #[Route('/api/notifications', name: 'user_notification', methods: ['GET'])]
    public function getUserNotifications(NotificationRepository $notificationRepository, SerializerInterface $serializer) : JsonResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $notifications = $notificationRepository->findLastSevenDaysNotifications($user);

        foreach ($notifications as $notification) {
            $this->logger->info($notification->getContent());
        }

        $jsonNotifications = $serializer->serialize($notifications, 'json', ["groups" => "getNotifications"]);

        return new JsonResponse($jsonNotifications, Response::HTTP_OK, [], true);
    }
}
