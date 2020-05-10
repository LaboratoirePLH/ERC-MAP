<?php

namespace App\Repository;

use App\Entity\Attestation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Attestation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attestation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attestation[]    findAll()
 * @method Attestation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttestationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attestation::class);
    }

    public function findByElement($elementId)
    {
        $queryBuilder = $this->createQueryBuilder("att");
        $queryBuilder->leftJoin("att.contientElements", "ce");
        $queryBuilder->leftJoin("ce.element", "e");
        $queryBuilder->where("e.id = :eId");
        $queryBuilder->setParameter(":eId", $elementId);
        return $queryBuilder->getQuery()->getResult();
    }
}
