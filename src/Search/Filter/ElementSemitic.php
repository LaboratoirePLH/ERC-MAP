<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class ElementSemitic extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We resolve all the elements
        $elements = self::toArray(
            self::resolveElements($entity, $sortedData)
        );

        $data = array_unique(array_map(function ($element) {
            return (strlen(strval($element['betaCode'] ?? null)) == 0) ? 'yes' : 'no';
        }, $elements));

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data) {
            return count(array_intersect($crit['values'], $data)) == count($crit['values']);
        }, $criteria)));
    }
}
