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
            ->join('s.titrePrincipal', 'titrePrincipal')
            ->join('s.typeSource', 'typeSource')
            ->join('s.categorieSource', 'categorieSource')
            ->join('s.createur', 'createur')
            ->join('s.dernierEditeur', 'dernierEditeur')
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
                'partial s.{id, dateModification, citation, urlTexte, urlImage}',
                'titrePrincipal',
                'typeSource',
                'categorieSource',
                'createur',
                'dernierEditeur',
            ])
            ->from('App\Entity\Source', 's')
            ->join('s.titrePrincipal', 'titrePrincipal')
            ->join('s.typeSource', 'typeSource')
            ->join('s.categorieSource', 'categorieSource')
            ->join('s.createur', 'createur')
            ->join('s.dernierEditeur', 'dernierEditeur')
            ->where('s.id = ?1')
            ->orderBy('s.dateModification', 'DESC')
            ->setParameter(1, $id)
            ->getQuery();

        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);

        return $query->getOneOrNullResult();
    }
}
