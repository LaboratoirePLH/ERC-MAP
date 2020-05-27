<?php

namespace App\Repository;

use App\Entity\Biblio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Biblio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Biblio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Biblio[]    findAll()
 * @method Biblio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BibliographyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Biblio::class);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('b')
            ->select('b', 'v', 'eb', 'sb')
            ->leftJoin('b.verrou', 'v')
            ->leftJoin('b.elementBiblios', 'eb')
            ->leftJoin('b.sourceBiblios', 'sb')
            ->getQuery()
            ->getResult();
    }
}
