<?php

namespace App\Repository;

use App\Entity\Ressource;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ressource>
 *
 * @method Ressource|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ressource|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ressource[]    findAll()
 * @method Ressource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RessourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ressource::class);
    }

    public function save(Ressource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ressource $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllPublicWithPagination($page = 0, $pageSize = 10): Paginator
    {
        $firstResult = ($page - 1) * $pageSize;

        $query = $this->createQueryBuilder('r')
            ->innerJoin('r.relationType', 'rtt')
            ->andWhere('rtt.id = :relationType')
            ->andWhere('r.isValid = true')
            ->andWhere('r.$isPublished = true')
            ->setParameter('relationType', 1)
            ->orderBy('r.createdAt', 'DESC');

        $query->setFirstResult($firstResult);
        $query->setMaxResults($pageSize);

        $query->getQuery();

        return new Paginator($query, true);
    }

    /**
     * @throws Exception
     */
    public function getAllResourcesByRelationsByCategory($user_id, $relation_type_id, $category_id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *
            FROM ressource
            INNER JOIN ressource_relation_type ON ressource.id = ressource_relation_type.ressource_id
            WHERE ressource.creator_id IN (
                SELECT 
                    CASE 
                        WHEN relation.sender_id = "user".id THEN relation.receiver_id
                        ELSE relation.sender_id
                    END as other_user_id
                FROM "user"
                INNER JOIN relation ON "user".id = relation.receiver_id OR "user".id = relation.sender_id
                INNER JOIN relation_type ON relation.relation_type_id = relation_type.id
                WHERE "user".id = :user_id
                AND relation.relation_type_id = :relation_type_id
            )
            AND ressource_relation_type.relation_type_id IN (
                SELECT relation_type_id
                FROM (
                    SELECT 
                        CASE 
                            WHEN relation.sender_id = "user".id THEN relation.receiver_id
                            ELSE relation.sender_id
                        END as other_user_id, 
                        relation_type.id as relation_type_id
                    FROM "user"
                    INNER JOIN relation ON "user".id = relation.receiver_id OR "user".id = relation.sender_id
                    INNER JOIN relation_type ON relation.relation_type_id = relation_type.id
                    WHERE "user".id = :user_id
                ) as subquery
                WHERE subquery.other_user_id = ressource.creator_id
            )
            AND ressource.category_id = :category_id
            AND (ressource.is_valid = true AND ressource.is_published = true)
            ORDER BY ressource.created_at DESC
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'user_id' => $user_id,
            'relation_type_id' => $relation_type_id,
            'category_id' => $category_id,
        ]);

        return $resultSet->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getAllResourcesByRelationsType($user_id, $relation_type_id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *
            FROM ressource
            INNER JOIN ressource_relation_type ON ressource.id = ressource_relation_type.ressource_id
            WHERE ressource.creator_id IN (
                SELECT 
                    CASE 
                        WHEN relation.sender_id = "user".id THEN relation.receiver_id
                        ELSE relation.sender_id
                    END as other_user_id
                FROM "user"
                INNER JOIN relation ON "user".id = relation.receiver_id OR "user".id = relation.sender_id
                INNER JOIN relation_type ON relation.relation_type_id = relation_type.id
                WHERE "user".id = :user_id
                AND relation.relation_type_id = :relation_type_id
            )
            AND ressource_relation_type.relation_type_id IN (
                SELECT relation_type_id
                FROM (
                    SELECT 
                        CASE 
                            WHEN relation.sender_id = "user".id THEN relation.receiver_id
                            ELSE relation.sender_id
                        END as other_user_id, 
                        relation_type.id as relation_type_id
                    FROM "user"
                    INNER JOIN relation ON "user".id = relation.receiver_id OR "user".id = relation.sender_id
                    INNER JOIN relation_type ON relation.relation_type_id = relation_type.id
                    WHERE "user".id = :user_id
                ) as subquery
                WHERE subquery.other_user_id = ressource.creator_id
            )
            AND (ressource.is_valid = true AND ressource.is_published = true)
            ORDER BY ressource.created_at DESC
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'user_id' => $user_id,
            'relation_type_id' => $relation_type_id,
        ]);

        return $resultSet->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getAllResourcesWithoutRelationTypeWithoutCategory($user_id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *
            FROM ressource
            INNER JOIN ressource_relation_type ON ressource.id = ressource_relation_type.ressource_id
            WHERE ressource.creator_id IN (
                SELECT 
                    CASE 
                        WHEN relation.sender_id = "user".id THEN relation.receiver_id
                        ELSE relation.sender_id
                    END as other_user_id
                FROM "user"
                INNER JOIN relation ON "user".id = relation.receiver_id OR "user".id = relation.sender_id
                INNER JOIN relation_type ON relation.relation_type_id = relation_type.id
                WHERE "user".id = :user_id
            )
            AND (ressource_relation_type.relation_type_id IN
                (
                    SELECT relation_type_id
                    FROM (
                        SELECT
                            CASE
                                WHEN relation.sender_id = "user".id THEN relation.receiver_id
                                ELSE relation.sender_id
                            END as other_user_id,
                            relation_type.id as relation_type_id
                        FROM "user"
                        INNER JOIN relation ON "user".id = relation.receiver_id OR "user".id = relation.sender_id
                        INNER JOIN relation_type ON relation.relation_type_id = relation_type.id
                        WHERE "user".id = :user_id
                    ) as subquery
                    WHERE subquery.other_user_id = ressource.creator_id
                )
                OR ressource_relation_type.relation_type_id = 1)
            AND (ressource.is_valid = true AND ressource.is_published = true)
            ORDER BY ressource.created_at DESC
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'user_id' => $user_id,
        ]);

        return $resultSet->fetchAllAssociative();
    }

    /**
     * @throws Exception
     */
    public function getAllResourcesByCategoryWithourRelationType($user_id, $category_id): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *
            FROM ressource
            INNER JOIN ressource_relation_type ON ressource.id = ressource_relation_type.ressource_id
            WHERE ressource.creator_id IN (
                SELECT 
                    CASE 
                        WHEN relation.sender_id = "user".id THEN relation.receiver_id
                        ELSE relation.sender_id
                    END as other_user_id
                FROM "user"
                INNER JOIN relation ON "user".id = relation.receiver_id OR "user".id = relation.sender_id
                INNER JOIN relation_type ON relation.relation_type_id = relation_type.id
                WHERE "user".id = :user_id
            )
            AND (ressource_relation_type.relation_type_id IN
                (
                    SELECT relation_type_id
                    FROM (
                        SELECT
                            CASE
                                WHEN relation.sender_id = "user".id THEN relation.receiver_id
                                ELSE relation.sender_id
                            END as other_user_id,
                            relation_type.id as relation_type_id
                        FROM "user"
                        INNER JOIN relation ON "user".id = relation.receiver_id OR "user".id = relation.sender_id
                        INNER JOIN relation_type ON relation.relation_type_id = relation_type.id
                        WHERE "user".id = :user_id
                    ) as subquery
                    WHERE subquery.other_user_id = ressource.creator_id
                )
                OR ressource_relation_type.relation_type_id = 1)
            AND ressource.category_id = :category_id
            AND (ressource.is_valid = true AND ressource.is_published = true)
            ORDER BY ressource.created_at DESC
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'user_id' => $user_id,
            'category_id' => $category_id,
        ]);

        return $resultSet->fetchAllAssociative();
    }

    public function getAllWithPaginationByRelations($friends_ids, $page = 0, $pageSize = 10): Paginator
    {
        $firstResult = ($page - 1) * $pageSize;

        $query = $this->createQueryBuilder('r')
            ->andWhere('r.creator IN (:relations)')
            ->setParameter('relations', $friends_ids)
            ->orderBy('r.createdAt', 'DESC');

        $query->setFirstResult($firstResult);
        $query->setMaxResults($pageSize);

        $query->getQuery();

        return new Paginator($query, true);
    }

    public function getAllWithPaginationById($ids, $page = 0, $pageSize = 10): Paginator
    {
        $firstResult = ($page - 1) * $pageSize;

        $query = $this->createQueryBuilder('r')
            ->andWhere('r.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->orderBy('r.createdAt', 'DESC');

        $query->setFirstResult($firstResult);
        $query->setMaxResults($pageSize);

        $query->getQuery();

        return new Paginator($query, true);
    }

    /**
     * @throws Exception
     */
    public function getOneUserResources($user_id, $research_user_id, $page = 0, $pageSize = 10): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT *
            FROM ressource
            INNER JOIN ressource_relation_type ON ressource.id = ressource_relation_type.ressource_id
            WHERE ressource.creator_id = :research_user_id
            AND (
                ressource_relation_type.relation_type_id = 1
                OR ressource_relation_type.relation_type_id IN (
                    SELECT relation_type_id
                    FROM relation
                    WHERE (relation.sender_id = :research_user_id AND relation.receiver_id = :user_id) OR (relation.sender_id = :user_id AND relation.receiver_id = :research_user_id)
                ))
            AND (ressource.is_valid = true AND ressource.is_published = true)
            ORDER BY ressource.created_at DESC
        ';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery([
            'user_id' => $user_id,
            'research_user_id' => $research_user_id,
        ]);

        return $resultSet->fetchAllAssociative();
    }

}
