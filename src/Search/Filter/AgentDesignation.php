<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class AgentDesignation extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the designations of the resolved agents
        $agents = self::resolveAgents($entity, $sortedData);
        $data = array_filter(array_column($agents, 'designation'));

        // Remove accents and tags, convert to lower case (with mb_strtolower)
        $data     = array_map(array('self', 'cleanStringValue'), $data);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data) {
            $strict = ($crit['mode'] ?? 'loose') === 'strict';
            $crit = self::cleanStringValue($crit['value']);
            // We require the criteria value to be present in data
            return !!count(array_filter($data, function ($d) use ($crit, $strict) {
                // If strict, we try to match with a regex with \b
                return $strict ? preg_match('/\b' . $crit . '\b/', $d) === 1 : (stristr($d, $crit) !== false);
            }));
        }, $criteria)));
    }
}
