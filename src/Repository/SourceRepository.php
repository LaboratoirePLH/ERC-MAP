<?php

namespace App\Repository;

use App\Entity\Source;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Source|null find($id, $lockMode = null, $lockVersion = null)
 * @method Source|null findOneBy(array $criteria, array $orderBy = null)
 * @method Source[]    findAll()
 * @method Source[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Source::class);
    }

    public function findByElement($elementId)
    {
        $queryBuilder = $this->createQueryBuilder("s");
        $queryBuilder->leftJoin("s.attestations", "att");
        $queryBuilder->leftJoin("att.contientElements", "ce");
        $queryBuilder->leftJoin("ce.element", "e");
        $queryBuilder->where("e.id = :eId");
        $queryBuilder->setParameter(":eId", $elementId);
        return $queryBuilder->getQuery()->getResult();
    }
}
