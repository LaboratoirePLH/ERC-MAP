<?php

namespace App\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class DatabaseActivitySubscriber implements EventSubscriber
{
    private $cache;

    public function __construct(TagAwareCacheInterface $mapCache)
    {
        $this->cache = $mapCache;
    }

    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents()
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself
    public function postPersist(LifecycleEventArgs $args)
    {
        $this->clearRelatedCache($args);
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        $this->clearRelatedCache($args);
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $this->clearRelatedCache($args);
    }

    private function clearRelatedCache(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        $rc = new \ReflectionClass($entity);
        $entityType = $rc->getShortName();

        $this->cache->invalidateTags([$entityType]);
    }
}