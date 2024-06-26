<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class MorphologicalForms extends AbstractFilter
{
    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We resolve the attestations
        $attestations = self::toArray(
            self::resolveAttestations($entity, $sortedData)
        );
        // For each attestation, we get their contextual elements
        $contextualElements = array_column($attestations, 'elements');
        // For each contextual element of a single attestation, we get the morphological state
        $data = array_filter(array_map(function ($attestation_ce) {
            $morphologicalStates = array_unique(array_filter(array_map(function ($ce) {
                return $ce['etatMorphologique'] ?? null;
            }, $attestation_ce)));
            return $morphologicalStates ?: null;
        }, $contextualElements));

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            $crit = array_filter($crit['values']);

            // We count the matched criteria values
            $matched = 0;
            foreach ($data as $d) {
                // $d contains all the contextual element morphological states linked to a single attestation
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
