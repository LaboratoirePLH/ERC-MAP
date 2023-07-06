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

    protected function baseQuery()
    {
        return $this->createQueryBuilder('s')
            ->select(
                'PARTIAL s.{id, inSitu, version, estDatee, dateCreation, dateModification, traduireFr, traduireEn}',
                // 't',
                // 'tsu',
                // 'm',
                'PARTIAL ld.{id, grandeRegion}',
                'PARTIAL lo.{id, grandeRegion}',
                'PARTIAL ldgr.{id, nomFr, nomEn}',
                'PARTIAL logr.{id, nomFr, nomEn}',
                'PARTIAL v.{id, date_fin}',
                'PARTIAL vc.{id, prenomNom}',
                'PARTIAL d.{id, anteQuem, postQuem}',
                'PARTIAL c.{id, prenomNom}',
                'PARTIAL de.{id, prenomNom}',
                'PARTIAL l.{id, nomFr, nomEn}',
                'sb',
                'PARTIAL b.{id, titreAbrege}',
                'PARTIAL tso.{id, nomFr, nomEn}',
                'PARTIAL cs.{id, nomFr, nomEn}',
                'PARTIAL p.{id, nomFr, nomEn}'
            )
            // ->leftJoin('s.titrePrincipal', 't')
            // ->leftJoin('s.typeSupport', 'tsu')
            // ->leftJoin('s.materiau', 'm')
            ->leftJoin('s.lieuDecouverte', 'ld')
            ->leftJoin('ld.grandeRegion', 'ldgr')
            ->leftJoin('s.lieuOrigine', 'lo')
            ->leftJoin('lo.grandeRegion', 'logr')
            ->leftJoin('s.verrou', 'v')
            ->leftJoin('v.createur', 'vc')
            ->leftJoin('s.datation', 'd')
            ->leftJoin('s.createur', 'c')
            ->leftJoin('s.dernierEditeur', 'de')
            ->leftJoin('s.langues', 'l')
            ->leftJoin('s.sourceBiblios', 'sb')
            ->leftJoin('sb.biblio', 'b')
            ->leftJoin('s.typeSources', 'tso')
            ->leftJoin('s.categorieSource', 'cs')
            ->leftJoin('s.projet', 'p');
    }

    public function findByElement($elementId)
    {
        $query = $this->baseQuery()
            ->leftJoin('s.attestations', 'att')
            ->leftJoin('att.contientElements', 'ce')
            ->leftJoin('ce.element', 'e')
            ->where('e.id = :eId')
            ->setParameter(':eId', $elementId)
            ->getQuery();

        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);
        return $query->getResult();
    }

    public function findAll(): array
    {
        $query = $this->baseQuery()->getQuery();

        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);
        return $query->getResult();
    }

    public function findForCorpusState(): array
    {
        $query = $this->createQueryBuilder('s')
            ->select(
                'PARTIAL s.{id}',
                'ld',
                'lo',
                'PARTIAL ldgr.{id, nomFr, nomEn, progression}',
                'PARTIAL ldsr.{id, nomFr, nomEn, progression}',
                'PARTIAL logr.{id, nomFr, nomEn, progression}',
                'PARTIAL losr.{id, nomFr, nomEn, progression}',
                'sb',
                'PARTIAL b.{id,auteurBiblio, titreAbrege, annee}',
                'PARTIAL a.{id}',
                'PARTIAL aef.{id,openAccess}'
            )
            ->leftJoin('s.lieuDecouverte', 'ld')
            ->leftJoin('s.lieuOrigine', 'lo')
            ->leftJoin('ld.grandeRegion', 'ldgr')
            ->leftJoin('ld.sousRegion', 'ldsr')
            ->leftJoin('lo.grandeRegion', 'logr')
            ->leftJoin('lo.sousRegion', 'losr')
            ->leftJoin('s.sourceBiblios', 'sb')
            ->leftJoin('sb.biblio', 'b')
            ->leftJoin('s.attestations', 'a')
            ->leftJoin('a.etatFiche', 'aef')
            ->getQuery();

        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);
        return $query->getResult();
    }
}
