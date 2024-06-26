<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Functions extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved locations
        $localisations = self::resolveLocalisations($entity, $sortedData);
        // If we have at least one criteria with the direct flag, we compute the direct locations
        $hasDirectFilter = !!count(array_column($criteria, "direct"));
        $directLocalisations = $hasDirectFilter ? self::resolveLocalisations($entity, $sortedData, false) : [];

        // For each location, we get its functions IDs
        $data = array_map(function ($el) {
            return array_column($el['fonctions'] ?? [], 'id');
        }, $localisations);
        $directData = array_map(function ($el) {
            return array_column($el['fonctions'] ?? [], 'id');
        }, $directLocalisations);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data, $directData) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            $isDirect = ($crit['direct'] ?? 'indirect') === 'direct';
            $filterData = $isDirect ? $directData : $data;

            $crit = array_filter($crit['values']);

            // We count the matched criteria values
            $matched = 0;
            foreach ($filterData as $d) {
                // $d contains all the functions linked to a single location
                // If we require all values to be matched we must find at least as many as the criteria values
                // Else we only need one
                if (count(array_intersect($crit, $d)) >= ($requireAll ? count($crit) : 1)) {
                    return true;
                }
            }

            return false;
        }, $criteria)));
    }
}
