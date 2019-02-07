<?php

namespace App\Repository;

use App\Entity\Source;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Source|null find($id, $lockMode = null, $lockVersion = null)
 * @method Source|null findOneBy(array $criteria, array $orderBy = null)
 * @method Source[]    findAll()
 * @method Source[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Source::class);
    }

    /**
     * @return Source[] Returns an array of Source objects
     */
    public function getSimpleList()
    {
        $query = $this->createQueryBuilder('source')
            ->select([
                'partial s.{id, dateModification}',
                'titrePrincipal',
                'typeSource',
                'categorieSource',
                'createur',
                'dernierEditeur',
            ])
            ->from('App\Entity\Source', 's')
            ->leftJoin('s.titrePrincipal', 'titrePrincipal')
            ->leftJoin('s.typeSource', 'typeSource')
            ->leftJoin('s.categorieSource', 'categorieSource')
            ->leftJoin('s.createur', 'createur')
            ->leftJoin('s.dernierEditeur', 'dernierEditeur')
            ->orderBy('s.dateModification', 'DESC')
            ->getQuery();

        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);

        return $query->getResult();
    }

    /**
     * @return Source|null Returns a Source object
     */
    public function getRecord(int $id): ?Source
    {
        $query = $this->createQueryBuilder('source')
            ->select([
                's',
                'typeSource',
                'materiau',
                'typeSupport',
                'auteurs',
                'langues',
                'titrePrincipal',
                'titresCites',
                'datation',
                'sourceBiblios',
                'createur',
                'dernierEditeur',
            ])
            ->from('App\Entity\Source', 's')
            ->leftJoin('s.typeSource', 'typeSource')
            ->leftJoin('s.materiau', 'materiau')
            ->leftJoin('s.typeSupport', 'typeSupport')
            ->leftJoin('s.auteurs', 'auteurs')
            ->leftJoin('s.langues', 'langues')
            ->leftJoin('s.titrePrincipal', 'titrePrincipal')
            ->leftJoin('s.titresCites', 'titresCites')
            ->leftJoin('s.datation', 'datation')
            ->leftJoin('s.sourceBiblios', 'sourceBiblios')
            ->leftJoin('s.createur', 'createur')
            ->leftJoin('s.dernierEditeur', 'dernierEditeur')
            ->where('s.id = ?1')
            ->setParameter(1, $id)
            ->getQuery();

        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);

        return $query->getOneOrNullResult();
    }
}
