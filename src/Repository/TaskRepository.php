<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * @param $value
     * @return int|mixed|string
     */
    public function findAllAvailable($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.dueTo >= :val')
            ->andWhere('t.status != :status')
            ->setParameter('val', $value)
            ->setParameter('status', 'done')
            ->orderBy('t.dueTo', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return Task[] Returns an array of Task objects
    //  */

    public function findOverDue($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.dueTo < :val')
            ->andWhere('t.status != :status')
            ->setParameter('val', $value)
            ->setParameter('status', 'done')
            ->orderBy('t.dueTo', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
