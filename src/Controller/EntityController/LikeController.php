<?php

namespace App\Controller\EntityController;

use App\Entity\Like;
use App\Entity\Notification;
use App\Entity\User;
use App\Repository\LikeRepository;
use App\Repository\NotificationRepository;
use App\Repository\RessourceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[OA\Tag(name: "Like")]
    #[OA\Response(
        response: 200,
        description: "Returns all likes of one user",
        content: new Model(type: like::class)
    )]
    #[Route('/api/like', name: 'all_likes', methods: ['GET'])]
    public function getAllLike(Request $request,
                               UserRepository$userRepository,
                               LikeRepository $likeRepository,
                               ): JsonResponse
    {
        $user = $this->getUser();
        $likes = $likeRepository->findBy(['user_like' => $user]);
        return $this->json($likes, 200, [], ['groups' => 'getLikes']);
    }


    #[OA\Tag(name: "Like")]
    #[OA\RequestBody(
        content: new OA\JsonContent(
            type: "object",
            properties: [
                new OA\Property(
                    property: "ressource_id",
                    type: "integer",
                    example: 1
                )
            ]
        )
    )]
    #[OA\Response(
        response: 201,
        description: "Returns the created like",
        content: new Model(type: Like::class)
    )]
    #[Route('/api/like', name: 'create_like', methods: ['POST'])]
    public function createLike(Request $request,
                               UserRepository $userRepository,
                               RessourceRepository $resourceRepository,
                               NotificationRepository $notificationRepository,
                               EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $resource_id = $data['ressource_id'];

        /** @var User $user */
        $user = $this->getUser();

        $resource = $resourceRepository->findOneBy(['id' => $resource_id]);
        $resource_creator = $resource->getCreator();

        $like = new Like();
        $like->setUserLike($user);
        $like->setRessourceLike($resource);
        $like->setIsLiked(true);
        $em->persist($like);

        $notification_content = $user->getPseudo() . ' a aimé votre ressource';

        $notification = new Notification($user, $resource_creator, 'like', $notification_content);
        $notification->setResource($resource);
        $em->persist($notification);

        $em->flush();
        return $this->json($like, 201, [], ['groups' => 'createLike']);
    }

    #[OA\Tag(name: "Like")]
    #[OA\Parameter(name: "id", description: "like id", in: "path", required: true, example: 1)]
    #[OA\Response(
        response: 204,
        description: "Returns the deleted like",
    )]
    #[Route('/api/like/{id}', name: 'delete_like', methods: ['DELETE'])]
    public function deleteLike(Request $request,
                               LikeRepository $likeRepository,
                               EntityManagerInterface $em,
                               $id): JsonResponse
    {
        $like = $likeRepository->findOneBy(['id' => $id]);
        $em->remove($like);
        $em->flush();
        return $this->json($like, 204, [], ['groups' => 'getLikes']);
    }
}
