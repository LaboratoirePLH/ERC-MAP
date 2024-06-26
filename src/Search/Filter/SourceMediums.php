<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class SourceMediums extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved sources
        $data = self::toArray(
            self::resolveSources($entity, $sortedData)
        );

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
                    // Single ID is medium category
                    if (array_key_exists('categorieSupport', $d) && $c[0] == ($d['categorieSupport']['id'] ?? null)) {
                        if (count($c) === 2) {
                            // Double ID is medium category + medium type
                            if (array_key_exists('typeSupport', $d) && $c[1] == $d['typeSupport']['id']) {
                                $matched++;
                                break; // Break the inner foreach since we found a source matching this criteria value
                            }
                        } else {
                            $matched++;
                            break; // Break the inner foreach since we found a source matching this criteria value
                        }
                    }
                }
            }

            // If we require all values to be matched we must find at least as many as the criteria values
            // Else we only need one
            return $matched >= ($requireAll ? count($crit) : 1);
        }, $criteria)));
    }
}
