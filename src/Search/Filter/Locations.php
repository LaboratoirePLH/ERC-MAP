<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Locations extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved locations
        $data = self::resolveLocalisations($entity, $sortedData);
        // If we have at least one criteria with the direct flag, we compute the direct locations
        $hasDirectFilter = !!count(array_column($criteria, "direct"));
        $directData = $hasDirectFilter ? self::resolveLocalisations($entity, $sortedData, false) : [];

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data, $directData) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            $isDirect = ($crit['direct'] ?? 'indirect') === 'direct';
            $filterData = $isDirect ? $directData : $data;

            // Each criteria entry value is a json encoded array
            $crit = array_map('json_decode', array_filter($crit['values']));

            // We count the matched criteria values
            $matched = 0;
            foreach ($crit as $c) {
                foreach ($filterData as $d) {
                    switch (count($c)) {
                        case 1: // Single ID is greater region
                            if (array_key_exists('grandeRegion', $d) && $c[0] === ($d['grandeRegion']['id'] ?? null)) {
                                $matched++;
                                break 2; // Break the inner foreach since we found a location matching this criteria value
                            }
                            break;
                        case 2: // Double ID is greater region + sub region
                            if ((array_key_exists('grandeRegion', $d) && $c[0] == ($d['grandeRegion']['id'] ?? null))
                                && (array_key_exists('sousRegion', $d) && $c[1] == ($d['sousRegion']['id'] ?? null))
                            ) {
                                $matched++;
                                break 2; // Break the inner foreach since we found a location matching this criteria value
                            }
                            break;
                        case 3: // Triple values is greater region + sub region (can be empty) + (pleiades ID or city name)
                            if ((array_key_exists('grandeRegion', $d) && $c[0] == ($d['grandeRegion']['id'] ?? null))
                                && ($c[1] == 0 ? true : (array_key_exists('sousRegion', $d) && $c[1] == ($d['sousRegion']['id'] ?? null)))
                                && (is_numeric($c[2]) ? ($c[2] == ($d['pleiadesVille'] ?? 0)) : ($c[2] == ($d['nomVille'] ?? '')))
                            ) {
                                $matched++;
                                break 2; // Break the inner foreach since we found a location matching this criteria value
                            }
                            break;
                    }
                }
            }

            // If we require all values to be matched we must find at least as many as the criteria values
            // Else we only need one
            return $matched >= ($requireAll ? count($crit) : 1);
        }, $criteria)));
    }
}
