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

    /*
    public function findOneBySomeField($value): ?Source
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
