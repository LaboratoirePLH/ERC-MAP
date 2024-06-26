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

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data) {
            $strict = ($crit['mode'] ?? 'loose') === 'strict';
            $crit = self::cleanStringValue($crit['value']);
            // We require the criteria value to be present in data
            return !!count(array_filter($data, function ($d) use ($crit, $strict) {
                $d_no_brackets = trim(str_replace(['[', ']', '{', '}', '-'], '', $d));
                // If strict, we try to match with a regex with \b
                return $strict
                    ? (preg_match('/\b' . $crit . '\b/', $d) === 1 || preg_match('/\b' . $crit . '\b/', $d_no_brackets) === 1)
                    : (stristr($d_no_brackets, $crit) !== false || stristr($d, $crit) !== false);
            }));
        }, $criteria)));
    }
}
