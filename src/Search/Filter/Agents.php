<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Agents extends AbstractFilter {

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved agents
        $data = self::resolveAgents($entity, $sortedData);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function($crit) use ($data) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            // Each criteria entry value is a json encoded array
            $crit = array_map('json_decode', $crit['values']);

            // We count the matched criteria values
            $matched = 0;
            foreach($crit as $c){
                foreach($data as $d){
                    // $a[0] equals 'activite' or 'agentivite'
                    // $a[1] is the ID of the activite/agentivite
                    // So we get all the IDs of the activites/agentivites subarray,
                    // We convert them to integers and check for a match
                    $ids = array_map('intval', array_column($d[$c[0] . 's'] ?? [], 'id'));
                    if(in_array($c[1], $ids)){
                        $matched++;
                    break; // Break the inner foreach since we found an agent matching this criteria value
                    }
                }
            }

            // If we require all values to be matched we must find at least as many as the criteria values
            // Else we only need one
            return $matched >= ($requireAll ? count($crit) : 1);

        }, $criteria)));
    }
}