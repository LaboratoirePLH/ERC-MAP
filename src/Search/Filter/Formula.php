<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Formula extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved attestations
        $data = self::toArray(
            self::resolveAttestations($entity, $sortedData)
        );

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';

            // We count the matched criteria values
            $matched = 0;
            foreach ($crit['values'] as $c) {
                foreach ($data as $d) {
                    $value = array_key_exists('formule1', $d) ? ($d['formule1']['formule'] ?? '') : '';
                    if (strlen($value) > 0 && strstr($value, $c) !== false) {
                        $matched++;
                        break;
                    }
                }
            }

            // If we require all values to be matched we must find at least as many as the criteria values
            // Else we only need one
            return $matched >= ($requireAll ? count($crit['values']) : 1);
        }, $criteria)));
    }
}
