<?php

namespace App\Repository;

use App\Entity\Chercheur;
use App\Entity\RechercheEnregistree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RechercheEnregistree|null find($id, $lockMode = null, $lockVersion = null)
 * @method RechercheEnregistree|null findOneBy(array $criteria, array $orderBy = null)
 * @method RechercheEnregistree[]    findAll()
 * @method RechercheEnregistree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RechercheEnregistreeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RechercheEnregistree::class);
    }

    /**
     * @return RechercheEnregistree[] Returns an array of RechercheEnregistree objects
     */
    public function findAllByChercheur(Chercheur $chercheur): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.createur', 'c')
            ->andWhere('c.id = :createur')
            ->setParameter('createur', $chercheur)
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
