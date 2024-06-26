<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Comments extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the comments of everything
        $sources      = self::toArray(self::resolveSources($entity, $sortedData));
        $attestations = self::toArray(self::resolveAttestations($entity, $sortedData));
        $elements     = self::toArray(self::resolveElements($entity, $sortedData));
        $datations    = self::resolveDatations($entity, $sortedData);
        $locations    = self::resolveLocalisations($entity, $sortedData);
        $agents       = self::resolveAgents($entity, $sortedData);

        $data = array_filter(array_merge(
            array_column($sources, 'commentaireFr'),
            array_column($sources, 'commentaireEn'),

            array_column($attestations, 'commentaireFr'),
            array_column($attestations, 'commentaireEn'),

            array_column($elements, 'commentaireFr'),
            array_column($elements, 'commentaireEn'),

            array_column($datations, 'commentaireFr'),
            array_column($datations, 'commentaireEn'),

            array_column($locations, 'commentaireFr'),
            array_column($locations, 'commentaireEn'),

            array_column($agents, 'commentaireFr'),
            array_column($agents, 'commentaireEn')
        ));

        // Remove accents and tags, convert to lower case (with mb_strtolower)
        $data     = array_map(array('self', 'cleanStringValue'), $data);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need only truthy values to accept the data
        return !in_array(false, (array_map(function ($crit) use ($data) {
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
