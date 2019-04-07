<?php

namespace App\Repository;

use App\Entity\VerrouEntite;
use App\Entity\Source;
use App\Entity\Attestation;
use App\Entity\Element;
use App\Entity\Chercheur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method VerrouEntite|null find($id, $lockMode = null, $lockVersion = null)
 * @method VerrouEntite|null findOneBy(array $criteria, array $orderBy = null)
 * @method VerrouEntite[]    findAll()
 * @method VerrouEntite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VerrouEntiteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VerrouEntite::class);
    }

    public function fetch($entite){
        $this->purge();
        $qb = $this->createQueryBuilder('verrou')
                   ->from('App\Entity\VerrouEntite', 'v');

        switch(true){
            case $entite instanceof Source:
                $qb = $qb->where(":e MEMBER OF v.sources");
                break;
                case $entite instanceof Attestation:
                $qb = $qb->where(":e MEMBER OF v.attestations");
                break;
                case $entite instanceof Element:
                $qb = $qb->where(":e MEMBER OF v.elements");
                break;
        }
        $qb = $qb->setParameter('e', $entite);
        $verrou = $qb->getQuery()
                     ->getOneOrNullResult();
        if($verrou !== null && $verrou->getDateFin() <= new \DateTime()){
            $this->remove($verrou);
            $verrou = null;
        }

        return $verrou;
    }

    public function create($entite, Chercheur $user, $minutes){
        $date = new \DateTime();
        date_add($date, new \DateInterval("PT".$minutes."M"));

        $v = $this->fetch($entite);

        if($v !== null){
            return $v;
        }
        $verrou = new VerrouEntite();
        $verrou->setDateFin($date);
        $verrou->setCreateur($user);

        switch(true){
            case $entite instanceof Source:
                $source = $entite;
                break;
            case $entite instanceof Attestation:
                $source = $entite->getSource();
                break;
            case $entite instanceof Element:
                // echo "Element";
                break;
        }
        $verrou->addSource($source);
        foreach($source->getAttestations() as $att){
            $verrou->addAttestation($att);
        }

        $this->getEntityManager()->persist($verrou);
        $this->getEntityManager()->flush();
        return $verrou;
    }

    public function remove(VerrouEntite $verrou) {
        $this->getEntityManager()->remove($verrou);
        $this->getEntityManager()->flush();
        $this->purge();
    }

    public function purge(){
        $this->createQueryBuilder('verrou')
             ->delete('App\Entity\VerrouEntite', 'v')
             ->where('v.date_fin < :date')
             ->setParameter(':date', new \DateTime())
             ->getQuery()
             ->getResult();
    }
}
