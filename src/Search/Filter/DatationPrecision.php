<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class DatationPrecision extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the datation fiability value of the resolved sources
        $sources = self::toArray(
            self::resolveSources($entity, $sortedData)
        );
        $data = array_column($sources, 'fiabiliteDatation');

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data) {
            // We check all the found datations against the criteria entry
            // If at least one datation is acceptable, we return true immediately
            foreach ($data as $d) {
                if (self::evaluateOperation($d, $crit['operator'], $crit['value'])) {
                    return true;
                }
            }
            // No datation was found acceptable, return false
            return false;
        }, $criteria)));
    }
}
