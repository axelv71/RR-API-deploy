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

class CommentController extends AbstractController
{
    #[Route('/api/comments', name: 'comments', methods: ["GET"])]
    public function getAllComments(CommentRepository $repository, SerializerInterface $serializer) : JsonResponse
    {
        $commentList = $repository->findAll();
        $jsonCommentList = $serializer->serialize($commentList, "json", ["groups"=>"getComments"]);
        return new JsonResponse($jsonCommentList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/comments/{id}', name: 'oneComment', methods: ["GET"])]
    public function getOneComment(Comment $comment, SerializerInterface $serializer) : JsonResponse
    {
        $jsonComment = $serializer->serialize($comment, "json", ["groups"=>"getComments"]);
        return new JsonResponse($jsonComment, Response::HTTP_OK, [], true);
    }

    #[Route('/api/comments/{id}', name: 'deleteComment', methods: ["DELETE"])]
    public function deleteComment(Comment $comment, EntityManagerInterface $em) : JsonResponse
    {
        $em->remove($comment);
        $em->flush();
        return new JsonResponse("Comment deleted", Response::HTTP_OK, [], true);
    }

    #[Route('/api/comments/{id}', name: 'updateComment', methods: ["PUT"])]
    public function updateComment(Comment $comment, EntityManagerInterface $em, Request $request, SerializerInterface $serializer) : JsonResponse
    {
        $jsonComment = $request->getContent();
        $updatedComment = $serializer->deserialize($jsonComment, Comment::class, "json", [AbstractNormalizer::OBJECT_TO_POPULATE => $comment]);
        $em->persist($updatedComment);
        $em->flush();
        return new JsonResponse("Comment updated", Response::HTTP_OK, [], true);
    }

    #[Route('/api/comments', name: 'addComment', methods: ["POST"])]
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
