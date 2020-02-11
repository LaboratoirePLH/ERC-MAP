<?php

namespace App\Repository;

use App\Entity\IndexRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method IndexRecherche|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexRecherche|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexRecherche[]    findAll()
 * @method IndexRecherche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexRechercheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndexRecherche::class);
    }

    public function fullRebuild()
    {
        return $this->rebuildByEntityType('Source')
             + $this->rebuildByEntityType('Attestation')
             + $this->rebuildByEntityType('Element');
    }

    public function rebuildByEntityType(string $entityType)
    {
        // Ensure $entityType has correct case
        $entityType = \ucfirst(\strtolower($entityType));

        // Remove all of entity type
        $this->createQueryBuilder('clear_index')
             ->delete("App\Entity\IndexRecherche", "i")
             ->where('i.entite = :entityType')
             ->setParameter('entityType', $entityType)
             ->getQuery()
             ->execute();

        // Select all entities of type
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT e FROM \App\Entity\\$entityType e");
        $all = $query->getResult();

        // var_dump($entityType, count($all), $query->getDQL());die;

        // For each entity
        foreach($all as $entity){
            // call toArray function
            $entityData = $entity->toArray();
            // Clean data to optimize it for indexation
            $entityData = $this->cleanData($entityData);

            // store given data
            $newEntry = new IndexRecherche;
            $newEntry->setEntite($entityType);
            $newEntry->setId($entity->getId());
            $newEntry->setData($entityData);
            $em->persist($newEntry);
        }

        $em->flush();
        return count($all);
    }

    public function cleanData(array $entityData): ?array
    {
        // array_filter et strip_tags recursif
        foreach($entityData as &$value){
            if(is_string($value)){
                $value = trim(html_entity_decode(strip_tags($value)));
            }
            else if(is_array($value)) {
                $value = $this->cleanData($value);
            }
        }
        return array_filter($entityData);
    }
}
