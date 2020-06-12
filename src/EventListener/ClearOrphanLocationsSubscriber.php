<?php

namespace App\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class ClearOrphanLocationsSubscriber implements EventSubscriber
{
    protected $_shouldClearOrphanLocations = false;

    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents()
    {
        return [
            Events::preRemove,
            Events::preUpdate,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    // callback methods must be called exactly like the events they listen to;
    // they receive an argument of type LifecycleEventArgs, which gives you access
    // to both the entity object of the event and the entity manager itself

    public function preUpdate(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof \App\Entity\Source) {
            $fields = ['lieuOrigine', 'lieuDecouverte'];
        } else if ($args->getEntity() instanceof \App\Entity\Interfaces\Located) {
            $fields = ['localisation'];
        } else {
            return;
        }
        foreach ($fields as $field) {
            // If we changed a location field and the previous value wans't null (we disconnect an existing location), maybe we have orphans
            if ($args->hasChangedField($field) && null !== $args->getOldValue($field)) {
                $this->_shouldClearOrphanLocations = true;
                break;
            }
        }
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof \App\Entity\Source) {
            $methods = ['getLieuOrigine', 'getLieuDecouverte'];
        } else if ($args->getEntity() instanceof \App\Entity\Interfaces\Located) {
            $methods = ['getLocalisation'];
        } else {
            return;
        }
        foreach ($methods as $method) {
            // If entity contained a non-null location field (we disconnect an existing location), maybe we have orphans
            if (null !== $args->getObject()->$method()) {
                $this->_shouldClearOrphanLocations = true;
                break;
            }
        }
    }

    public function postRemove(LifecycleEventArgs $args)
    {
        // dump($this->_shouldClearOrphanLocations);
        // die;
        if ($this->_shouldClearOrphanLocations) {
            $this->clearOrphanLocalisations($args->getEntityManager());
        }
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        // dump($this->_shouldClearOrphanLocations);
        // die;
        if ($this->_shouldClearOrphanLocations) {
            $this->clearOrphanLocalisations($args->getEntityManager());
        }
    }

    private function clearOrphanLocalisations($em)
    {
        // We get the list of active locations
        $activeLocations = array_merge(
            $em->createQuery("SELECT IDENTITY(e.lieuDecouverte) FROM App\Entity\Source e WHERE e.lieuDecouverte IS NOT NULL")->getScalarResult(),
            $em->createQuery("SELECT IDENTITY(e.lieuOrigine) FROM App\Entity\Source e WHERE e.lieuOrigine IS NOT NULL")->getScalarResult(),
            $em->createQuery("SELECT IDENTITY(e.localisation) FROM App\Entity\Attestation e WHERE e.localisation IS NOT NULL")->getScalarResult(),
            $em->createQuery("SELECT IDENTITY(e.localisation) FROM App\Entity\Agent e WHERE e.localisation IS NOT NULL")->getScalarResult(),
            $em->createQuery("SELECT IDENTITY(e.localisation) FROM App\Entity\Element e WHERE e.localisation IS NOT NULL")->getScalarResult()
        );
        // Flatten and remove duplicates
        $activeLocations = array_values(array_unique(array_reduce($activeLocations, 'array_merge', [])));

        // Delete Locations not in the array
        $criteria = new Criteria();
        $criteria->where($criteria->expr()->notIn('id', $activeLocations));
        $criteria->setMaxResults(1000);
        $inactiveLocations = $em->getRepository(\App\Entity\Localisation::class)->matching($criteria);
        foreach ($inactiveLocations as $l) {
            $em->remove($l);
        }
    }
}
