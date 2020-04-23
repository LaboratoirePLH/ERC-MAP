<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class CitiesSites extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved locations
        $localisations = self::resolveLocalisations($entity, $sortedData);

        // We get the ID (or name if no ID set) of the pleiades City of each location
        $cities = array_filter(array_map(function ($l) {
            return $l['pleiadesVille'] ?? $l['nomVille'] ?? null;
        }, $localisations));
        // We get the ID (or name if no ID set) of the pleiades Site of each location
        $sites = array_filter(array_map(function ($l) {
            return $l['pleiadesSite'] ?? $l['nomSite'] ?? null;
        }, $localisations));
        $data = [];
        foreach ($cities as $city) {
            $data[] = json_encode(['city', $city]);
        }
        foreach ($sites as $site) {
            $data[] = json_encode(['site', $site]);
        }

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            $crit = array_filter($crit['values']);

            // If requireAll is true, we require all the values in the criteria to be present (intersection count equals to criteria values count)
            // If requireAll is false, we require at least one of the value in the criteria to be present
            return count(array_intersect($crit, $data)) >= ($requireAll ? count($crit) : 1);
        }, $criteria)));
    }
}
