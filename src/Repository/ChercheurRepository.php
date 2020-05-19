<?php

namespace App\Repository;

use App\Entity\Chercheur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Chercheur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Chercheur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Chercheur[]    findAll()
 * @method Chercheur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChercheurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chercheur::class);
    }

    public function activate($id): Chercheur
    {
        $user = $this->find($id);
        $user->setActif(true);
        $this->getEntityManager()->flush();
        return $user;
    }
}
