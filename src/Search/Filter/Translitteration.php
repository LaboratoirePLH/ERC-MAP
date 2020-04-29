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


        // Remove accents
        $data     = array_map(function ($d) {
            return \App\Utils\StringHelper::removeAccents($d);
        }, $data);
        $criteria = array_map(function ($d) {
            return \App\Utils\StringHelper::removeAccents($d);
        }, $criteria);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data) {
            // We require the criteria value to be present in data
            return !!count(array_filter($data, function ($d) use ($crit) {
                $d_no_tags = strip_tags($d);
                $d_no_brackets = trim(str_replace(['[', ']', '{', '}', '-'], '', $d_no_tags));
                return stristr($d_no_brackets, $crit) !== false || stristr($d_no_tags, $crit) !== false || stristr($d, $crit) !== false;
            }));
        }, $criteria)));
    }
}
