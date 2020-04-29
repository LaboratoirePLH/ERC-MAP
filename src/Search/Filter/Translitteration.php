<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Translitteration extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We resolve the attestations
        $attestations = self::toArray(
            self::resolveAttestations($entity, $sortedData)
        );
        // For each attestation, we get its acts IDs
        $data = array_column($attestations, "translitteration");

        // Remove accents and tags, convert to lower case (with mb_strtolower)
        $data     = array_map(array('self', 'cleanStringValue'), $data);
        $criteria = array_map(array('self', 'cleanStringValue'), $criteria);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data) {
            // We require the criteria value to be present in data
            return !!count(array_filter($data, function ($d) use ($crit) {
                $d_no_brackets = trim(str_replace(['[', ']', '{', '}', '-'], '', $d));
                return stristr($d_no_brackets, $crit) !== false || stristr($d, $crit) !== false;
            }));
        }, $criteria)));
    }
}
