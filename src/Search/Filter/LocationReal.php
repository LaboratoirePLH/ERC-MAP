<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class LocationReal extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We resolve the localisations
        $localisations = self::resolveLocalisations($entity, $sortedData);

        $data = array_unique(array_map(function ($localisation) {
            return boolval($localisation['reel'] ?? null) ? 'yes' : 'no';
        }, $localisations));

        // This criteria will only have a single entry with one or two values (duplicates and empty values are removed)
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data) {
            return count(array_intersect($crit['values'], $data)) == count($crit['values']);
        }, $criteria)));
    }
}
