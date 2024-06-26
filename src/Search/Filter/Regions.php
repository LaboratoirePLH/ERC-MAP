<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Regions extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved locations
        $data = self::resolveLocalisations($entity, $sortedData);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            // Each criteria entry value is a json encoded array
            $crit = array_map('json_decode', array_filter($crit['values']));

            // We count the matched criteria values
            $matched = 0;
            foreach ($crit as $c) {
                foreach ($data as $d) {
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
                    }
                }
            }

            // If we require all values to be matched we must find at least as many as the criteria values
            // Else we only need one
            return $matched >= ($requireAll ? count($crit) : 1);
        }, $criteria)));
    }
}
