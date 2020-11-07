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

    private function baseQuery()
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'd', 'l', 'c', 'de', 's', 't', 'tsu', 'm', 'ld', 'lo', 'sv', 'sd', 'sc', 'sde', 'sl', 'sb', 'b')
            ->leftJoin('a.datation', 'd')
            ->leftJoin('a.localisation', 'l')
            ->leftJoin('a.createur', 'c')
            ->leftJoin('a.dernierEditeur', 'de')
            ->leftJoin('a.source', 's')
            ->leftJoin('s.titrePrincipal', 't')
            ->leftJoin('s.typeSupport', 'tsu')
            ->leftJoin('s.materiau', 'm')
            ->leftJoin('s.lieuDecouverte', 'ld')
            ->leftJoin('s.lieuOrigine', 'lo')
            ->leftJoin('s.verrou', 'sv')
            ->leftJoin('s.datation', 'sd')
            ->leftJoin('s.createur', 'sc')
            ->leftJoin('s.dernierEditeur', 'sde')
            ->leftJoin('s.langues', 'sl')
            ->leftJoin('s.sourceBiblios', 'sb')
            ->leftJoin('sb.biblio', 'b');
    }

    public function findByElement($elementId)
    {
        return $this->baseQuery()
            ->leftJoin('a.contientElements', 'ce')
            ->leftJoin('ce.element', 'e')
            ->where('e.id = :eId')
            ->setParameter(':eId', $elementId)
            ->getQuery()
            ->getResult();
    }

    public function findAll()
    {
        return $this->baseQuery()
            ->getQuery()
            ->getResult();
    }

    public function findBySource($sourceId)
    {
        return $this->baseQuery()
            ->where('s.id = :sId')
            ->setParameter(':sId', $sourceId)
            ->getQuery()
            ->getResult();
    }
}
