<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class TestimonyActs extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We resolve the attestations
        $attestations = self::toArray(
            self::resolveAttestations($entity, $sortedData)
        );
        // For each attestation, we get its acts IDs
        $data = array_map(function ($el) {
            return array_column($el['pratiques'] ?? [], 'id');
        }, $attestations);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            $crit = array_filter($crit['values']);

            foreach ($data as $d) {
                // $d contains all the acts of an attestation
                // If we require all values to be matched we must find at least as many as the criteria values
                // Else we only need one
                if (count(array_intersect($crit, $d)) >= ($requireAll ? count($crit) : 1)) {
                    return true;
                }
            }
        }, $criteria)));
    }
}
