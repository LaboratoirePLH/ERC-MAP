<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class AgentGenders extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We resolve the agents
        $agents = self::resolveAgents($entity, $sortedData);
        // For each agent, we get its genders IDs
        $data = array_map(function ($el) {
            return array_column($el['genres'] ?? [], 'id');
        }, $agents);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            $crit = array_filter($crit['values']);

            // We count the matched criteria values
            $matched = 0;
            foreach ($data as $d) {
                // $d contains all the genders of an agent
                // If we require all values to be matched we must find at least as many as the criteria values
                // Else we only need one
                if (count(array_intersect($crit, $d)) >= ($requireAll ? count($crit) : 1)) {
                    return true;
                }
            }
        }, $criteria)));
    }
}
