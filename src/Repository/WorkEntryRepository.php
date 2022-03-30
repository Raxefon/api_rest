<?php

namespace App\Repository;

use App\Entity\WorkEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkEntry[]    findAll()
 * @method WorkEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkEntry::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(WorkEntry $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(WorkEntry $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return WorkEntryByUserId[] Returns an array of User objects
    //  */
    public function findWorkEntryByUserId($id)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.deletedAt is NULL')
            ->andWhere('w.user = :val')
            ->setParameter('val', $id)
            ->orderBy('w.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return WorkEntry[] Returns an array of User objects
    //  */
    public function findWorkEntryById($id): ?WorkEntry
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.deletedAt is NULL')
            ->andWhere('w.id = :val')
            ->setParameter('val', $id)
            ->orderBy('w.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return WorkEntry[] Returns an array of WorkEntry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkEntry
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
