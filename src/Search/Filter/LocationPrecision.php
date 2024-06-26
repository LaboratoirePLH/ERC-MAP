<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class LocationPrecision extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved locations and compute their precision
        $locations = self::resolveLocalisations($entity, $sortedData);
        $data = array_map('self::getLocationPrecision', $locations);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data) {
            // We check all the found locations against the criteria entry
            // If at least one location is acceptable, we return true immediately
            foreach ($data as $d) {
                if (self::evaluateOperation($d, $crit['operator'], $crit['value'])) {
                    return true;
                }
            }
            // No location was found acceptable, return false
            return false;
        }, $criteria)));
    }

    private static function getLocationPrecision(array $location): int
    {
        if (!is_null($location['nomSite'] ?? null)) {
            return 1;
        }
        if (!is_null($location['nomVille'] ?? null)) {
            return 2;
        }
        if (!is_null($location['sousRegion'] ?? null)) {
            return 3;
        }
        if (!is_null($location['grandeRegion'] ?? null)) {
            return 4;
        }
        return 0;
    }
}
