<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\RessourceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Attributes as OA;

class CommentController extends AbstractController
{
    /**
     * @param CommentRepository $repository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */

    #[Route('/api/comments', name: 'comments', methods: ["GET"])]
    #[OA\Response(
        response: 200,
        description: "Returns the list of comments",
        content: new OA\JsonContent(ref: new Model(type: Comment::class))
    )]
    #[OA\Tag(name: "Comment")]
    public function getAllComments(CommentRepository $repository, SerializerInterface $serializer) : JsonResponse
    {
        $commentList = $repository->findAll();
        $jsonCommentList = $serializer->serialize($commentList, "json", ["groups"=>"getComments"]);
        return new JsonResponse($jsonCommentList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/comments/{id}', name: 'oneComment', methods: ["GET"])]
    #[OA\Response(
        response: 200,
        description: "Returns one comment",
        content: new OA\JsonContent(ref: new Model(type: Comment::class)),
    )]
    #[OA\Tag(name: "Comment")]
    #[OA\Parameter(
        name: "id",
        description: "The id of the comment",
        in: "path",
        required: true,
        example: 1
    )]
    public function getOneComment(Comment $comment, SerializerInterface $serializer) : JsonResponse
    {
        $jsonComment = $serializer->serialize($comment, "json", ["groups"=>"getComments"]);
        return new JsonResponse($jsonComment, Response::HTTP_OK, [], true);
    }

    #[Route('/api/comments/{id}', name: 'deleteComment', methods: ["DELETE"])]
    #[OA\Response(
        response: 204,
        description: "Delete one comment",
        content: new Model(type: Comment::class, groups: ["default"]),
    )]
    #[OA\Tag(name: "Comment")]
    #[OA\Parameter(
        name: "id",
        description: "The id of the comment",
        in: "path",
        required: true,
        example: 1
    )]
    public function deleteComment(Comment $comment, EntityManagerInterface $em) : JsonResponse
    {
        $em->remove($comment);
        $em->flush();
        return new JsonResponse("Comment deleted", Response::HTTP_NO_CONTENT, [], true);
    }

    #[Route('/api/comments', name: 'addComment', methods: ["POST"])]
    #[OA\Response(
        response: 201,
        description: "Add one comment",
        content: new Model(type: Comment::class, groups: ["comment"])
    )]
    #[OA\Tag(name: "Comment")]
    #[OA\RequestBody(
        description: "Add a new comment",
        attachables: [
            new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "content",
                            type: "string",
                            example: "This is a comment"
                        ),
                        new OA\Property(
                            property: "user",
                            type: "integer",
                            example: 1
                        ),
                        new OA\Property(
                            property: "ressource",
                            type: "integer",
                            example: 1
                        )
                    ]
                )
            )
        ]

    )]
    public function addComment(EntityManagerInterface $em,
                               Request $request,
                               SerializerInterface $serializer,
                               UserRepository $userRepository,
                               RessourceRepository $ressourceRepository) : JsonResponse
    {
        $jsonComment = $request->getContent();
        $comment = $serializer->deserialize($jsonComment, Comment::class, "json");

        $content = $request->toArray();

        $creatorId = $content["creator"]["id"];
        $ressourceId = $content["ressource"]["id"];

        $user = $userRepository->find($creatorId);
        $ressource = $ressourceRepository->find($ressourceId);

        $comment->setCreator($user);
        $comment->setRessource($ressource);

        if($user){
            $user->addComment($comment);
            $em->persist($user);
        }
        if($ressource){
            $ressource->addComment($comment);
            $em->persist($ressource);
        }


        $em->persist($comment);
        $em->flush();

        return new JsonResponse("Comment added", Response::HTTP_CREATED, ["Location" => $this->generateUrl("oneComment", ["id" => $comment->getId()])], true);
    }
}
