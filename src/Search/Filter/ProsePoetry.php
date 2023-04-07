<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class ProsePoetry extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We resolve the attestations
        $attestations = self::toArray(
            self::resolveAttestations($entity, $sortedData)
        );
        $data = array_map(function ($attestation) {
            $attestation_data = array_filter([
                ($attestation['prose'] ?? false) ? 'prose' : null,
                ($attestation['poesie'] ?? false) ? 'poesie' : null,
            ]);
            return $attestation_data;
        }, $attestations);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data) {
            foreach ($data as $d) {
                // We require all the values in the criteria to be present (intersection count equals to criteria values count)
                if (count(array_intersect($crit['values'], $d)) >= count($crit['values'])) {
                    return true;
                }
            }
            return false;
        }, $criteria)));
    }
}
