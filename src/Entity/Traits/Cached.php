<?php

namespace App\Entity\Traits;

use App\Utils\CacheEngine;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;

trait Cached
{
    /**
     * @ORM\PostPersist
     * @ORM\PostUpdate
     * @ORM\PostRemove
     */
    public function _clearRelatedCachedItems(){
        $rc = new \ReflectionClass($this);
        $entityType = $rc->getShortName();

        $cache = new CacheEngine;
        $cache->invalidateTags([$entityType]);
    }
}