<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

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

        $cache = new TagAwareAdapter(
            new FilesystemAdapter('.map.cache.inner')
        );
        $cache->invalidateTags([$entityType]);
    }
}