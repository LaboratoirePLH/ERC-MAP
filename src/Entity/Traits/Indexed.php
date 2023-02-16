<?php

namespace App\Entity\Traits;

use App\Entity\IndexRecherche;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\Event\LifecycleEventArgs;

trait Indexed
{
    /**
     * @ORM\PostPersist
     */
    public function _indexCreatedRecord(LifecycleEventArgs $args)
    {
        $rc = new \ReflectionClass($this);
        $entityType = $rc->getShortName();

        $args->getEntityManager()
            ->getRepository(IndexRecherche::class)
            ->rebuildEntry($entityType, $this->getId());
    }
    /**
     * @ORM\PostUpdate
     */
    public function _reindexUpdatedRecord(LifecycleEventArgs $args)
    {
        $rc = new \ReflectionClass($this);
        $entityType = $rc->getShortName();

        $args->getEntityManager()
            ->getRepository(IndexRecherche::class)
            ->rebuildEntry($entityType, $this->getId());
    }
    /**
     * @ORM\PreRemove
     */
    public function _deindexDeletedRecord(LifecycleEventArgs $args)
    {
        $rc = new \ReflectionClass($this);
        $entityType = $rc->getShortName();

        $args->getEntityManager()
            ->getRepository(IndexRecherche::class)
            ->deleteEntry($entityType, $this->getId());
    }
}
