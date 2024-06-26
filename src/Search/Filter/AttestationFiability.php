<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class AttestationFiability extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the fiability value of the resolved attestations
        $attestations = self::toArray(
            self::resolveAttestations($entity, $sortedData)
        );
        $data = array_column($attestations, 'fiabilite');

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data) {
            // We check all the found attestations against the criteria entry
            // If at least one attestation is acceptable, we return true immediately
            foreach ($data as $d) {
                if (self::evaluateOperation($d, $crit['operator'], $crit['value'])) {
                    return true;
                }
            }
            // No attestation was found acceptable, return false
            return false;
        }, $criteria)));
    }
}
