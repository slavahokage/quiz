<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\AdminAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdminAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminAnswer[]    findAll()
 * @method AdminAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminAnswerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdminAnswer::class);
    }


}
