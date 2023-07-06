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
            ->select(
                'PARTIAL a.{id, version, translitteration, extraitAvecRestitution, passage, dateCreation, dateModification, traduireFr, traduireEn}',
                // 'd',
                // 'l',
                'PARTIAL c.{id, prenomNom}',
                'PARTIAL de.{id, prenomNom}',
                'PARTIAL aef.{id,openAccess, nomFr, nomEn}',
                'PARTIAL s.{id}',
                // 't',
                // 'tsu',
                // 'm',
                // 'ld',
                // 'lo',
                'PARTIAL sv.{id, date_fin}',
                'PARTIAL svc.{id, prenomNom}',
                // 'sd',
                // 'sc',
                // 'sde',
                // 'sl',
                'sb',
                'b',
                'PARTIAL p.{id, nomFr, nomEn}'
            )
            // ->leftJoin('a.datation', 'd')
            // ->leftJoin('a.localisation', 'l')
            ->leftJoin('a.createur', 'c')
            ->leftJoin('a.dernierEditeur', 'de')
            ->leftJoin('a.etatFiche', 'aef')
            ->leftJoin('a.source', 's')
            // ->leftJoin('s.titrePrincipal', 't')
            // ->leftJoin('s.typeSupport', 'tsu')
            // ->leftJoin('s.materiau', 'm')
            // ->leftJoin('s.lieuDecouverte', 'ld')
            // ->leftJoin('s.lieuOrigine', 'lo')
            ->leftJoin('s.verrou', 'sv')
            ->leftJoin('sv.createur', 'svc')
            // ->leftJoin('s.datation', 'sd')
            // ->leftJoin('s.createur', 'sc')
            // ->leftJoin('s.dernierEditeur', 'sde')
            // ->leftJoin('s.langues', 'sl')
            ->leftJoin('s.sourceBiblios', 'sb')
            ->leftJoin('sb.biblio', 'b')
            ->leftJoin('s.projet', 'p');
    }

    public function findByElement($elementId)
    {
        $query = $this->baseQuery()
            ->leftJoin('a.contientElements', 'ce')
            ->leftJoin('ce.element', 'e')
            ->where('e.id = :eId')
            ->setParameter(':eId', $elementId)
            ->getQuery();

        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);
        return $query->getResult();
    }

    public function findAll()
    {
        $query = $this->baseQuery()
            ->getQuery();

        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);
        return $query->getResult();
    }

    public function findBySource($sourceId)
    {
        $query = $this->baseQuery()
            ->where('s.id = :sId')
            ->setParameter(':sId', $sourceId)
            ->getQuery();

        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);
        return $query->getResult();
    }
}
