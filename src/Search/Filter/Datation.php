<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Datation extends AbstractFilter {

    const DEFAULT_POST_QUEM = -3000;
    const DEFAULT_ANTE_QUEM = 3000;

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved datations
        $data = self::resolveDatations($entity, $sortedData);
        // dump($data);
        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function($crit) use ($data) {
            $criteriaPostQuem = is_numeric($crit['post_quem']) ? intval($crit['post_quem']) : null;
            $criteriaAnteQuem = is_numeric($crit['ante_quem']) ? intval($crit['ante_quem']) : null;
            $criteriaExact    = !!($crit['exact'] ?? false); // Convert to boolean

            // We check all the found datations against the criteria entry
            // If at least one datation is acceptable, we return true immediately
            foreach($data as $d){

                // If exact is checked, we need to have both PostQuem and AnteQuem criteria set
                // Otherwise we consider exact is not checked
                if($criteriaExact && !is_null($criteriaPostQuem) && !is_null($criteriaAnteQuem))
                {
                    // If exact is checked, we dismiss entity datations with a missing PostQuem/AnteQuem value
                    if(is_null($d['postQuem'] ?? null) || is_null($d['anteQuem'] ?? null))
                    {
                        continue;
                    }
                    if($criteriaPostQuem <= $d['postQuem'] && $d['anteQuem'] <= $criteriaAnteQuem)
                    {
                        return true;
                    }
                }
                else
                {
                    if(is_null($d['postQuem'] ?? null) && is_null($d['anteQuem'] ?? null)){
                        continue;
                    }
                    // To accept the record (in non-exact filtering), 2 conditions need to be met :
                    //  - datation PostQuem <= criteria AnteQuem
                    //  - datation AnteQuem >= criteria PostQuem
                    // Any missing value in those 4 will be replaced by default values :
                    //  - Default Post Quem = -3000
                    //  - Default Ante Quem = 3000
                    if(($d['postQuem'] ?? self::DEFAULT_POST_QUEM) <= ($criteriaAnteQuem ?? self::DEFAULT_ANTE_QUEM)
                        && ($d['anteQuem'] ?? self::DEFAULT_ANTE_QUEM) >= ($criteriaPostQuem ?? self::DEFAULT_POST_QUEM))
                    {
                        return true;
                    }
                }
            }
            // No datation was found acceptable, return false
            return false;

        }, $criteria)));
    }
}