<?php

namespace App\Entity\Traits;

use App\Entity\Localisation;
use App\Entity\Source;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping as ORM;

trait ShouldClearOrphanLocations
{
    /**
     * @ORM\PreUpdate
     */
    public function _onLocatedEntityUpdate(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Source) {
            $fields = ['lieuOrigine', 'lieuDecouverte'];
        } else {
            $fields = ['localisation'];
        }
        foreach ($fields as $field) {
            // If we changed a location field and the previous value wans't null (we disconnect an existing location), maybe we have orphans
            if ($args->hasChangedField($field) && null !== $args->getOldValue($field)) {
                $this->_clearOrphanLocalisations($args->getEntityManager());
                break;
            }
        }
    }

    /**
     * @ORM\PreRemove
     */
    public function _onLocatedEntityDelete(LifecycleEventArgs $args)
    {
        if ($args->getEntity() instanceof Source) {
            $methods = ['getLieuOrigine', 'getLieuDecouverte'];
        } else {
            $methods = ['getLocalisation'];
        }
        foreach ($methods as $method) {
            // If entity contained a non-null location field (we disconnect an existing location), maybe we have orphans
            if (null !== $args->getObject()->$method()) {
                $this->_clearOrphanLocalisations($args->getEntityManager());
                break;
            }
        }
    }

    private function _clearOrphanLocalisations($em)
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
        $inactiveLocations = $em->getRepository(Localisation::class)->matching($criteria);
        foreach ($inactiveLocations as $l) {
            $em->remove($l);
        }
    }
}
