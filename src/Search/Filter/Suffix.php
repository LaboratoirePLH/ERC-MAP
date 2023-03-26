<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Suffix extends AbstractFilter
{
    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the elements suffix states of the resolved attestations
        $attestations = self::toArray(
            self::resolveAttestations($entity, $sortedData)
        );
        $elements = array_reduce(
            $attestations,
            function ($result, $item) {
                return array_merge(
                    $result,
                    $item['elements'] ?? []
                );
            },
            []
        );

        $data = array_unique(array_map(function ($element) {
            return boolval($element['suffixe'] ?? false) ? 'yes' : 'no';
        }, $elements));

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(
            array_filter(
                array_map(function ($crit) use ($data) {
                    return count(array_intersect($crit['values'], $data)) == count($crit['values']);
                }, $criteria)
            )
        );
    }
}
