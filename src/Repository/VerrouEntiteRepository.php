<?php

namespace App\Repository;

use App\Entity\Attestation;
use App\Entity\Biblio;
use App\Entity\Chercheur;
use App\Entity\Element;
use App\Entity\Source;
use App\Entity\VerrouEntite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VerrouEntite|null find($id, $lockMode = null, $lockVersion = null)
 * @method VerrouEntite|null findOneBy(array $criteria, array $orderBy = null)
 * @method VerrouEntite[]    findAll()
 * @method VerrouEntite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerrouEntiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VerrouEntite::class);
    }

    public function fetch($entite)
    {
        $this->purge();
        $qb = $this->createQueryBuilder('verrou')
            ->from('App\Entity\VerrouEntite', 'v');

        $e = $entite;

        switch (true) {
            case $entite instanceof Attestation:
                $e = $entite->getSource();
            case $entite instanceof Source:
                $qb = $qb->where(":e MEMBER OF v.sources");
                break;
            case $entite instanceof Element:
                $qb = $qb->where(":e MEMBER OF v.elements");
                break;
            case $entite instanceof Biblio:
                $qb = $qb->where(":e MEMBER OF v.biblios");
                break;
        }
        $qb = $qb->setParameter('e', $e)
            ->setMaxResults(1);
        $verrou = $qb->getQuery()
            ->getOneOrNullResult();

        return $verrou;
    }

    public function create($entite, Chercheur $user, $minutes)
    {
        $date = new \DateTime();
        date_add($date, new \DateInterval("PT" . $minutes . "M"));

        $v = $this->fetch($entite);

        if ($v !== null) {
            return $v;
        }
        $verrou = new VerrouEntite();
        $verrou->setDateFin($date);
        $verrou->setCreateur($user);

        if ($entite instanceof Source || $entite instanceof Attestation) {
            if ($entite instanceof Source) {
                $source = $entite;
            } else {
                $source = $entite->getSource();
            }
            $verrou->addSource($source);
        } else if ($entite instanceof Element) {
            $verrou->addElement($entite);
        } else if ($entite instanceof Biblio) {
            $verrou->addBiblio($entite);
        }

        $this->getEntityManager()->persist($verrou);
        $this->getEntityManager()->flush();
        return $verrou;
    }

    public function remove(VerrouEntite $verrou)
    {
        $this->getEntityManager()->remove($verrou);
        $this->getEntityManager()->flush();
        $this->purge();
    }

    public function purge()
    {
        $this->createQueryBuilder('verrou')
            ->delete('App\Entity\VerrouEntite', 'v')
            ->where('v.date_fin < :date')
            ->setParameter(':date', new \DateTime())
            ->getQuery()
            ->getResult();
        $this->getEntityManager()->flush();
    }
}
