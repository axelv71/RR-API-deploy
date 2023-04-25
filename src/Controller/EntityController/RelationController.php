<?php

namespace App\Controller\EntityController;

use App\Entity\Notification;
use App\Entity\NotificationType;
use App\Entity\Relation;
use App\Entity\RelationType;
use App\Entity\User;
use App\Repository\NotificationTypeRepository;
use App\Repository\RelationRepository;
use App\Repository\RelationTypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RelationController extends AbstractController
{
    private LoggerInterface $logger;
    private NotificationType $notificationType;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger, NotificationTypeRepository $notificationTypeRepository)
    {
        $this->logger = $logger;
        $this->notificationType = $notificationTypeRepository->findOneBy(['name' => 'relation']);
    }

    /**
     * Get relation details.
     */
    #[Route('/api/relation/{id}', name: 'relation_details', methods: ['GET'])]
    #[OA\Tag(name: 'Relation')]
    #[OA\Response(response: 200, description: 'Return details of one relation')]
    #[OA\Parameter(name: 'id', description: 'The id of the relation', in: 'path', required: true, example: 1)]
    public function getOneRelation(Relation $relation, SerializerInterface $serializer): JsonResponse
    {
        $jsonRelation = $serializer->serialize($relation, 'json', ['groups' => 'relation:read']);

        return new JsonResponse($jsonRelation, Response::HTTP_OK, [], true);
    }

    /**
     * Add friend.
     */
    #[Route('/api/relation/add', name: 'add_relation', methods: ['POST'])]
    #[OA\Tag('Relation')]
    #[OA\RequestBody(description: 'Add friends', required: true, attachables: [
        new OA\MediaType(
            mediaType: 'application/json',
            schema: new OA\Schema(
                type: 'object',
                properties: [
                    new OA\Property(property: 'relationType', type: 'int', example: 1),
                    new OA\Property(property: 'receiver', type: 'int', example: 1),
                ]
            )
        ),
    ])]
    #[OA\Response(response: 201, description: 'Relation has been created successfully')]
    #[OA\Response(response: 409, description: 'A relation already exists between these two users')]
    public function add(Request $request, EntityManagerInterface $entityManage, UserRepository $userRepository, RelationTypeRepository $relationTypeRepository, RelationRepository $relationRepository, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $requestData = $request->toArray();
        /** @var User $sender */
        $sender = $this->getUser();
        $receiver = $userRepository->find($requestData['receiver']);
        $relationType = $relationTypeRepository->find($requestData['relationType']);

        $existingRelations = $relationRepository->findBy([
            'Sender' => $sender,
            'Receiver' => $receiver,
        ]);

        if (!$existingRelations) {
            $existingRelations = $relationRepository->findBy([
                'Sender' => $receiver,
                'Receiver' => $sender,
            ]);
        }

        if ($existingRelations) {
            foreach ($existingRelations as $existingRelation) {
                if ($existingRelation->getRelationType()->getId() === $relationType->getId()) {
                    $this->logger->info('Relation already exists between these two users');

                    $json = $serializer->serialize($existingRelation, 'json', ['groups' => 'relation:read']);

                    return new JsonResponse($json, Response::HTTP_CONFLICT, [], true);
                }
            }
        }

        $relation = Relation::create($sender, $receiver, $relationType);
        $relation->setUpdatedAt(new \DateTimeImmutable());

        $notification = Notification::create($sender, $receiver, $this->notificationType, "Vous avez reçu une demande d'amis de la part de ".$sender->getAccountName());
        $entityManage->persist($notification);

        $entityManage->persist($relation);
        $entityManage->flush();

        $url = $urlGenerator->generate('relation_details', ['id' => $relation->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        $json = $serializer->serialize($relation, 'json', ['groups' => 'relation:read']);

        return new JsonResponse($json, 201, ['location' => $url], true);
    }

    /**
     * Get all user relations.
     */
    #[Route('/api/relations', name: 'user_relations', methods: ['GET'])]
    #[OA\Tag('Relation')]
    #[OA\Response(response: 200, description: 'Return all relations of the user')]
    public function getUserRelations(RelationRepository $relationRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        /*$sender_relations = $relationRepository->findBy(['Sender' => $user]);
        $receiver_relations = $relationRepository->findBy(['Receiver' => $user]);
        $relations = array_merge($sender_relations, $receiver_relations);*/

        $relations = $relationRepository->retrieveAllRelationsByUser($user);

        $jsonRelations = $serializer->serialize($relations, 'json', ['groups' => 'relation:read']);

        return new JsonResponse($jsonRelations, Response::HTTP_OK, [], true);
    }

    /**
     * Get all user relations by relation type.
     */
    #[Route('/api/relations/{id}/relationtype', name: 'user_relations_relationtype', methods: ['GET'])]
    #[OA\Tag('Relation')]
    #[OA\Response(response: 200, description: 'Return all relations of the user')]
    public function getUserRelationByRelationType(RelationType $relationType, RelationRepository $relationRepository, SerializerInterface $serializer): JsonResponse
    {
        // dd($relationType);

        $user = $this->getUser();
        $sender_relations = $relationRepository->findBy(['Sender' => $user, 'relation_type' => $relationType]);
        $receiver_relations = $relationRepository->findBy(['Receiver' => $user, 'relation_type' => $relationType]);
        $relations = array_merge($sender_relations, $receiver_relations);

        $jsonRelations = $serializer->serialize($relations, 'json', ['groups' => 'relation:read']);

        return new JsonResponse($jsonRelations, Response::HTTP_OK, [], true);
    }

    /**
     * Accept friend request.
     */
    #[Route('/api/relation/{id}', name: 'relation_accept', methods: ['POST'])]
    #[OA\Tag('Relation')]
    #[OA\Parameter(name: 'id', description: 'Relation id', in: 'path', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'The relation has been accepted')]
    #[OA\Response(response: 401, description: 'You are not allowed to accept this relation')]
    public function acceptRelation(Relation $relation,
                                   EntityManagerInterface $entityManage,
                                   RelationRepository $relationRepository,
                                   RelationTypeRepository $relationTypeRepository,
                                   SerializerInterface $serializer): JsonResponse
    {
        if ($relation->getReceiver() !== $this->getUser()) {
            $json = $serializer->serialize(['error' => 'You are not the receiver of this relation'], 'json');

            return new JsonResponse($json, Response::HTTP_UNAUTHORIZED, [], true);
        }

        $relation->setIsAccepted(true);
        $relation->setUpdatedAt(new \DateTimeImmutable());
        $entityManage->persist($relation);


        $notification = Notification::create($relation->getReceiver(),
            $relation->getSender(),
            $this->notificationType,
            "Votre demande d'amis a été acceptée par ".$relation->getReceiver()->getAccountName());

        $entityManage->persist($notification);

        $entityManage->flush();

        $json = $serializer->serialize(['success' => 'Relation has been accepted'], 'json');

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    /**
     * Get not accepted user relations.
     */
    #[Route('/api/relations/notaccepted', name: 'relation_notaccepted', methods: ['GET'])]
    #[OA\Tag('Relation')]
    #[OA\Response(response: 200, description: 'Return not accepted relations of the user')]
    public function getNotAcceptedUserRelations(RelationRepository $relationRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        $relations = $relationRepository->findBy(['Receiver' => $user, 'isAccepted' => false]);
        $jsonRelations = $serializer->serialize($relations, 'json', ['groups' => 'relation:read']);

        return new JsonResponse($jsonRelations, Response::HTTP_OK, [], true);
    }

    /**
     * Get user accepted relations.
     */
    #[Route('/api/relations/accepted', name: 'relation_accepted', methods: ['GET'])]
    #[OA\Tag('Relation')]
    #[OA\Response(response: 200, description: 'Return accepted relations of the user')]
    public function getAcceptedUserRelations(RelationRepository $relationRepository, SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();
        $relations = $relationRepository->findBy(['Receiver' => $user, 'isAccepted' => true]);
        $jsonRelations = $serializer->serialize($relations, 'json', ['groups' => 'relation:read']);

        return new JsonResponse($jsonRelations, Response::HTTP_OK, [], true);
    }

    /**
     *  Delete relation.
     */
    #[Route('/api/relations/{id}', name: 'relation_delete', methods: ['DELETE'])]
    #[OA\Tag('Relation')]
    #[OA\Parameter(name: 'id', description: 'Relation id', in: 'path', required: true, example: 1)]
    #[OA\Response(response: 200, description: 'The relation has been deleted')]
    #[OA\Response(response: 401, description: 'You are not allowed to delete this relation')]
    public function deleteRelation(Relation $relation, EntityManagerInterface $entityManage, SerializerInterface $serializer): JsonResponse
    {
        if ($relation->getReceiver() !== $this->getUser() && $relation->getSender() !== $this->getUser()) {
            $json = $serializer->serialize(['error' => 'You are not allowed to delete this relation'], 'json');

            return new JsonResponse($json, Response::HTTP_UNAUTHORIZED, [], true);
        }

        $entityManage->remove($relation);
        $entityManage->flush();

        $json = $serializer->serialize(['success' => 'Relation has been deleted'], 'json');

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}
