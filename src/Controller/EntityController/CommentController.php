<?php

namespace App\Controller\EntityController;

use App\Entity\Comment;
use App\Entity\Notification;
use App\Entity\NotificationType;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\NotificationTypeRepository;
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
use Symfony\Component\Serializer\SerializerInterface;

class CommentController extends AbstractController
{
    private NotificationType $notificationType;

    public function __construct(NotificationTypeRepository $notificationTypeRepository)
    {
        $this->notificationType = $notificationTypeRepository->findOneBy(['name' => 'comment']);
    }

    #[Route('/api/comments', name: 'comments', methods: ['GET'])]
    #[OA\Tag(name: 'Comment')]
    public function getAllComments(CommentRepository $repository, SerializerInterface $serializer): JsonResponse
    {
        $commentList = $repository->findAll();
        $jsonCommentList = $serializer->serialize($commentList, 'json', ['groups' => 'getComments']);

        return new JsonResponse($jsonCommentList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/comments/{id}', name: 'oneComment', methods: ['GET'])]
    #[OA\Response(response: 200, description: 'Returns one comment', content: new OA\JsonContent(ref: new Model(type: Comment::class)))]
    #[OA\Tag(name: 'Comment')]
    #[OA\Parameter(name: 'id', description: 'The id of the comment', in: 'path', required: true, example: 1)]
    public function getOneComment(Comment $comment, SerializerInterface $serializer): JsonResponse
    {
        $jsonComment = $serializer->serialize($comment, 'json', ['groups' => 'getComments']);

        return new JsonResponse($jsonComment, Response::HTTP_OK, [], true);
    }

    #[Route('/api/comments/{id}', name: 'deleteComment', methods: ['DELETE'])]
    #[OA\Response(response: 204, description: 'Delete one comment', content: new Model(type: Comment::class, groups: ['default']))]
    #[OA\Tag(name: 'Comment')]
    #[OA\Parameter(name: 'id', description: 'The id of the comment', in: 'path', required: true, example: 1)]
    public function deleteComment(Comment $comment, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($comment);
        $em->flush();

        return new JsonResponse('Comment deleted', Response::HTTP_NO_CONTENT, [], true);
    }

    #[Route('/api/comments', name: 'addComment', methods: ['POST'])]
    #[OA\Response(response: 201, description: 'Add one comment', content: new Model(type: Comment::class, groups: ['comment']))]
    #[OA\Tag(name: 'Comment')]
    #[OA\RequestBody(description: 'Add a new comment', attachables: [
            new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'content',
                            type: 'string',
                            example: 'This is a comment'
                        ),
                        new OA\Property(
                            property: 'ressourceid',
                            type: 'integer',
                            example: 1
                        ),
                    ]
                )
            ),
        ])]
    public function addComment(EntityManagerInterface $em,
                               Request $request,
                               SerializerInterface $serializer,
                               UserRepository $userRepository,
                               RessourceRepository $ressourceRepository): JsonResponse
    {
        $jsonComment = $request->getContent();
        $comment = $serializer->deserialize($jsonComment, Comment::class, 'json');

        $content = $request->toArray();

        $ressourceId = $content['ressourceid'];

        /** @var User $user */
        $user = $this->getUser();
        $ressource = $ressourceRepository->find($ressourceId);

        $comment->setCreator($user);
        $comment->setRessource($ressource);

        if ($user) {
            $user->addComment($comment);
            $em->persist($user);
        }
        if ($ressource) {
            $ressource->addComment($comment);
            $em->persist($ressource);
        }

        $notification = Notification::create($user,
            $ressource->getCreator(),
            $this->notificationType,
            $user->getAccountName().' a commenté une de vos ressources ',
            $ressource);

        $em->persist($notification);

        $em->persist($comment);
        $em->flush();

        $jsonComment = $serializer->serialize($comment, 'json', ['groups' => 'getComments']);

        return new JsonResponse($jsonComment, Response::HTTP_CREATED, ['Location' => $this->generateUrl('oneComment', ['id' => $comment->getId()])], true);
    }
}
