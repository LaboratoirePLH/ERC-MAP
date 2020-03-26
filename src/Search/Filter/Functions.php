<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Functions extends AbstractFilter {

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved locations
        $localisations = self::resolveLocalisations($entity, $sortedData);

        // For each location, we get its functions IDs
        $data = array_map(function($el){
            return array_column($el['functions'] ?? [], 'id');
        }, $elements);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function($crit) use ($data) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            $crit = array_filter($crit['values']);

            // We count the matched criteria values
            $matched = 0;
            foreach($data as $d){
                // $d contains all the functions linked to a single location
                // If we require all values to be matched we must find at least as many as the criteria values
                // Else we only need one
                if(count(array_intersect($crit, $d)) >= ($requireAll ? count($crit) : 1)){
                    return true;
                }
            }

            return false;

        }, $criteria)));
    }
}