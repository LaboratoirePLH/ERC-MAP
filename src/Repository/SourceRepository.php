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
        return $this->createQueryBuilder('s')
            ->select('s', 't', 'tsu', 'm', 'ld', 'lo', 'v', 'd', 'c', 'de', 'l', 'sb', 'b', 'tso', 'cs')
            ->leftJoin('s.titrePrincipal', 't')
            ->leftJoin('s.typeSupport', 'tsu')
            ->leftJoin('s.materiau', 'm')
            ->leftJoin('s.lieuDecouverte', 'ld')
            ->leftJoin('s.lieuOrigine', 'lo')
            ->leftJoin('s.verrou', 'v')
            ->leftJoin('s.datation', 'd')
            ->leftJoin('s.createur', 'c')
            ->leftJoin('s.dernierEditeur', 'de')
            ->leftJoin('s.langues', 'l')
            ->leftJoin('s.sourceBiblios', 'sb')
            ->leftJoin('sb.biblio', 'b')
            ->leftJoin('s.typeSources', 'tso')
            ->leftJoin('tso.categorieSource', 'cs')
            ->leftJoin('s.attestations', 'att')
            ->leftJoin('att.contientElements', 'ce')
            ->leftJoin('ce.element', 'e')
            ->where('e.id = :eId')
            ->setParameter(':eId', $elementId)
            ->getQuery()
            ->getResult();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('s')
            ->select('s', 't', 'tsu', 'm', 'ld', 'lo', 'v', 'd', 'c', 'de', 'l', 'sb', 'b', 'tso', 'cs')
            ->leftJoin('s.titrePrincipal', 't')
            ->leftJoin('s.typeSupport', 'tsu')
            ->leftJoin('s.materiau', 'm')
            ->leftJoin('s.lieuDecouverte', 'ld')
            ->leftJoin('s.lieuOrigine', 'lo')
            ->leftJoin('s.verrou', 'v')
            ->leftJoin('s.datation', 'd')
            ->leftJoin('s.createur', 'c')
            ->leftJoin('s.dernierEditeur', 'de')
            ->leftJoin('s.langues', 'l')
            ->leftJoin('s.sourceBiblios', 'sb')
            ->leftJoin('sb.biblio', 'b')
            ->leftJoin('s.typeSources', 'tso')
            ->leftJoin('tso.categorieSource', 'cs')
            ->getQuery()
            ->getResult();
    }
}
