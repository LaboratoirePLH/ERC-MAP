<?php

namespace App\Repository;

use App\Entity\Element;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Element|null find($id, $lockMode = null, $lockVersion = null)
 * @method Element|null findOneBy(array $criteria, array $orderBy = null)
 * @method Element[]    findAll()
 * @method Element[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Element::class);
    }

    public function findByAttestation($attestationId)
    {
        $queryBuilder = $this->createQueryBuilder("elt");
        $queryBuilder->leftJoin("elt.contientElements", "ce");
        $queryBuilder->leftJoin("ce.attestation", "att");
        $queryBuilder->where("att.id = :attId");
        $queryBuilder->setParameter(":attId", $attestationId);
        return $queryBuilder->getQuery()->getResult();
    }
}
