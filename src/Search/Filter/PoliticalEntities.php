<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class PoliticalEntities extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved locations
        $localisations = self::resolveLocalisations($entity, $sortedData);
        // If we have at least one criteria with the direct flag, we compute the direct locations
        $hasDirectFilter = !!count(array_column($criteria, "direct"));
        $directLocalisations = $hasDirectFilter ? self::resolveLocalisations($entity, $sortedData, false) : [];

        // We get the ID of the political entity (if any) of each location
        $data = array_filter(array_map(function ($l) {
            return ($l['entitePolitique'] ?? [])['id'] ?? null;
        }, $localisations));
        $directData = array_filter(array_map(function ($l) {
            return ($l['entitePolitique'] ?? [])['id'] ?? null;
        }, $directLocalisations));

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        /// We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data, $directData) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            $isDirect = ($crit['direct'] ?? 'indirect') === 'direct';
            $filterData = $isDirect ? $directData : $data;

            $crit = array_filter($crit['values']);

            // If requireAll is true, we require all the values in the criteria to be present (intersection count equals to criteria values count)
            // If requireAll is false, we require at least one of the value in the criteria to be present
            return count(array_intersect($crit, $filterData)) >= ($requireAll ? count($crit) : 1);
        }, $criteria)));
    }
}
