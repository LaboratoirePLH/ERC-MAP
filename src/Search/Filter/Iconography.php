<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Iconography extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We resolve all the sources
        $sources = self::toArray(
            self::resolveSources($entity, $sortedData)
        );

        $data = array_unique(array_map(function ($source) {
            return boolval($source['iconographie'] ?? null) ? 'yes' : 'no';
        }, $sources));

        // This criteria will only have a single entry with one or two values (duplicates and empty values are removed)
        // We need to match all the criteria values against the data
        return count(array_intersect($criteria, $data)) == count($criteria);
    }
}
