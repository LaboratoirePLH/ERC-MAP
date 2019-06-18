<?php

namespace App\Repository;

use App\Entity\Requetes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Requetes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Requetes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Requetes[]    findAll()
 * @method Requetes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequetesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Requetes::class);
    }

    // /**
    //  * @return Requetes[] Returns an array of Requetes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Requetes
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
