<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class DatationComments extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the comments of the resolved datations
        $datations = self::resolveDatations($entity, $sortedData);
        $data = array_filter(array_merge(
            array_column($datations, 'commentaireFr'),
            array_column($datations, 'commentaireEn')
        ));

        // Remove accents and tags, convert to lower case (with mb_strtolower)
        $data     = array_map(array('self', 'cleanStringValue'), $data);
        $criteria = array_map(array('self', 'cleanStringValue'), $criteria);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data) {
            // We require the criteria value to be present in data
            return !!count(array_filter($data, function ($d) use ($crit) {
                return stristr($d, $crit) !== false;
            }));
        }, $criteria)));
    }
}
