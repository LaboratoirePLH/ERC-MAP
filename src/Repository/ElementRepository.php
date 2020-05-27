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
        return $this->createQueryBuilder('e')
            ->select('e', 'v', 'l', 'c', 'de', 't', 'cat')
            ->leftJoin('e.verrou', 'v')
            ->leftJoin('e.localisation', 'l')
            ->leftJoin('e.createur', 'c')
            ->leftJoin('e.dernierEditeur', 'de')
            ->leftJoin('e.traductions', 't')
            ->leftJoin('e.categories', 'cat')
            ->leftJoin("e.contientElements", "ce")
            ->leftJoin("ce.attestation", "att")
            ->where("att.id = :attId")
            ->setParameter(":attId", $attestationId)
            ->getQuery()
            ->getResult();
    }

    public function findAll()
    {
        return $this->createQueryBuilder('e')
            ->select('e', 'v', 'l', 'c', 'de', 't', 'cat')
            ->leftJoin('e.verrou', 'v')
            ->leftJoin('e.localisation', 'l')
            ->leftJoin('e.createur', 'c')
            ->leftJoin('e.dernierEditeur', 'de')
            ->leftJoin('e.traductions', 't')
            ->leftJoin('e.categories', 'cat')
            ->getQuery()
            ->getResult();
    }
}
