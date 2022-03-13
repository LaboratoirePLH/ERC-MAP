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
        // If we have at least one criteria with the direct flag, we compute the direct locations
        $hasDirectFilter = !!count(array_column($criteria, "direct"));
        $directLocalisations = $hasDirectFilter ? self::resolveLocalisations($entity, $sortedData, false) : [];

        // We get the ID (or name if no ID set) of the pleiades City and pleiades Site of each location
        $data = array_filter(array_map(function ($l) {
            return [$l['pleiadesVille'] ?? $l['nomVille'] ?? null, ($l['pleiadesSite'] ?? '') . '-' . ($l['nomSite'] ?? '')];
        }, $localisations));
        $directData = array_filter(array_map(function ($l) {
            return [$l['pleiadesVille'] ?? $l['nomVille'] ?? null, ($l['pleiadesSite'] ?? '') . '-' . ($l['nomSite'] ?? '')];
        }, $directLocalisations));

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        $match = !!count(array_filter(array_map(function ($crit) use ($data, $directData) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            $isDirect = ($crit['direct'] ?? 'indirect') === 'direct';
            $filterData = $isDirect ? $directData : $data;

            // Each criteria entry value is a json encoded array
            $crit = array_map('json_decode', array_filter($crit['values']));

            // We count the matched criteria values
            $matched = 0;
            foreach ($crit as $c) {
                foreach ($filterData as $d) {
                    // Single ID is pleiades City ID/Name
                    if ($c[0] == $d[0]) {
                        if (count($c) === 2) {
                            // Double ID is pleiades Site ID/Name
                            if ($c[1] == $d[1]) {
                                $matched++;
                                break; // Break the inner foreach since we found a localisation matching this criteria value
                            }
                        } else {
                            $matched++;
                            break; // Break the inner foreach since we found a localisation matching this criteria value
                        }
                    }
                }
            }

            // If we require all values to be matched we must find at least as many as the criteria values
            // Else we only need one
            return $matched >= ($requireAll ? count($crit) : 1);
        }, $criteria)));

        return $match;
    }
}
