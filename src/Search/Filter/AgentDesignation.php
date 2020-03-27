<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class AgentDesignation extends AbstractFilter {

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the designations of the resolved agents
        $agents = self::resolveAgents($entity, $sortedData);
        $data = array_filter(array_column($agents, 'designation'));

        // Remove html and accents from data and criteria (cannot input HTML in criteria, but better safe than sorry)
        $data     = array_map(function($d){ return \App\Utils\StringHelper::removeAccents(strip_tags($d)); }, $data);
        $criteria = array_map(function($d){ return \App\Utils\StringHelper::removeAccents(strip_tags($d)); }, $criteria);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function($crit) use ($data) {
            // We require the criteria value to be present in data
            return !!count(array_filter($data, function($d) use ($crit){
                return stristr($d, $crit) !== false;
            }));
        }, $criteria)));
    }
}