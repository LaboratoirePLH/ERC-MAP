<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class DivinePowersCount extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved attestations
        $data = self::toArray(
            self::resolveAttestations($entity, $sortedData)
        );

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data) {
            // We check all the found attestations against the criteria entry
            // If at least one attestation is acceptable, we return true immediately

            foreach ($data as $d) {
                $value = array_key_exists('formule1', $d) ? ($d['formule1']['puissancesDivines'] ?? 0) : -1;
                if (self::evaluateOperation($value, $crit['operator'], $crit['value'])) {
                    return true;
                }
            }
            // No attestation was found acceptable, return false
            return false;
        }, $criteria)));
    }
}
