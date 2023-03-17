<?php

namespace App\Repository;

use App\Entity\Ressource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function getAllPublicWithPagination($page=0, $pageSize = 10): Paginator
    {
        $firstResult = ($page - 1) * $pageSize;

        $query = $this->createQueryBuilder('r')
            ->innerJoin('r.relationType', 'rtt')
            ->andWhere('rtt.id = :relationType')
            ->setParameter('relationType', 1)
            ->orderBy('r.createdAt', 'DESC');

        $query->setFirstResult($firstResult);
        $query->setMaxResults($pageSize);

        $query->getQuery();

        return new Paginator($query, true);
    }

    public function getAllWithPaginationByRelationsByCategory($friends_ids, $category_id, $page=0, $pageSize=10) : Paginator
    {
        $firstResult = ($page - 1) * $pageSize;

        $query = $this->createQueryBuilder('r')
            ->andWhere('r.creator IN (:relations)')
            ->andWhere('r.category = :category')
            ->setParameter('category', $category_id)
            ->setParameter('relations', $friends_ids)
            ->orderBy('r.createdAt', 'DESC');

        $query->setFirstResult($firstResult);
        $query->setMaxResults($pageSize);

        $query->getQuery();

        return new Paginator($query, true);
    }

    public function getAllWithPaginationByRelations($friends_ids, $page=0, $pageSize=10) : Paginator
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

//    /**
//     * @return Ressource[] Returns an array of Ressource objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ressource
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
