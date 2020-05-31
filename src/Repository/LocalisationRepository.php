<?php

namespace App\Repository;

use App\Entity\Localisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Localisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Localisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Localisation[]    findAll()
 * @method Localisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocalisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Localisation::class);
    }

    public function findAll()
    {
        // Do not select GrandeRegion and SousRegion, the geometry columns will dramatically slow the query
        return $this->createQueryBuilder('e')
            ->select('e', 'ep' , 't' , 'f')
            ->leftJoin('e.entitePolitique', 'ep')
            ->leftJoin('e.topographies', 't')
            ->leftJoin('e.fonctions', 'f')
            ->getQuery()
            ->getResult();
    }
}
