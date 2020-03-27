<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class TestimonyOccasions extends AbstractFilter {

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We resolve the attestations
        $attestations = self::toArray(
            self::resolveAttestations($entity, $sortedData)
        );
        // For each attestation, we get its occasions, and merge it in a single-level array (removing duplicates)
        $data = array_column($attestations, 'occasions');
        if(!empty($data))
        {
            $data = array_reduce(
                array_merge(
                    ...$data
                ),
                function($result, $item){
                    foreach($result as $r){
                        if($r['categorieOccasion']['id'] == $item['categorieOccasion']['id']
                            && $r['occasion']['id'] == $item['occasion']['id'])
                        {
                            return $result;
                        }
                    }
                    return array_merge($result, [$item]);
                },
                []
            );
        }

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function($crit) use ($data) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            // Each criteria entry value is a json encoded array
            $crit = array_map('json_decode', array_filter($crit['values']));

            // We count the matched criteria values
            $matched = 0;
            foreach($crit as $c){
                foreach($data as $d){
                    // Single ID is occasion category
                    if(array_key_exists('categorieOccasion', $d) && $c[0] == ($d['categorieOccasion']['id'] ?? null))
                    {
                        if(count($c) === 2)
                        {
                            // Double ID is occasion category + occasion type
                            if(array_key_exists('occasion', $d) && $c[1] == $d['occasion']['id'])
                            {
                                $matched++;
                                break; // Break the inner foreach since we found a occasion matching this criteria value
                            }
                        }
                        else
                        {
                            $matched++;
                            break; // Break the inner foreach since we found a occasion matching this criteria value
                        }
                    }
                }
            }

            // If we require all values to be matched we must find at least as many as the criteria values
            // Else we only need one
            return $matched >= ($requireAll ? count($crit) : 1);
        }, $criteria)));
    }
}