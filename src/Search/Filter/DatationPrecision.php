<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class DatationPrecision extends AbstractFilter {

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
        return !!count(array_filter(array_map(function($crit) use ($data) {
            // We require the criteria value to be present in data
            return in_array($crit, $data);
        }, $criteria)));
    }
}