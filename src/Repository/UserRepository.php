<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getTest()
    {
        $qb = $this->createQueryBuilder('u');
        return $qb->select('u.username', 'r.score', 'r.time')
            ->innerJoin('u', 'r', 'WITH', 'u.id = r.user_id');
        //return $this->findBy(array(), array('score' s=> 'DESC'));
    }

    public function getTopTreeResultsOfUsers($quiz)
    {
        $qb = $this->createQueryBuilder('u');
        return $qb->select('u.username', 'r.score', 'r.time')
            ->innerJoin('u.resultOfQuizzes', 'r')
            ->addOrderBy('r.score', 'DESC')
            ->addOrderBy('r.time', 'ASC')
            ->where('r.quiz = :quiz')
            ->setParameter('quiz', $quiz)
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function getAllResult($quiz)
    {
        $qb = $this->createQueryBuilder('u');
        return $qb->select('u.username', 'r.score', 'r.time')
            ->innerJoin('u.resultOfQuizzes', 'r')
            ->addOrderBy('r.score', 'DESC')
            ->addOrderBy('r.time', 'ASC')
            ->where('r.quiz = :quiz')
            ->setParameter('quiz', $quiz)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
