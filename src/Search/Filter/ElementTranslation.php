<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class ElementTranslation extends AbstractFilter {

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the translations of the resolved elements
        $elements = self::toArray(
            self::resolveElements($entity, $sortedData)
        );
        $data = array_reduce(
            $elements,
            function($result, $item){
                return array_merge(
                    $result,
                    array_reduce(
                        $item['traductions'] ?? [],
                        function($traductions, $trad){
                            return array_merge(
                                $traductions,
                                array_filter([$trad['nomFr'], $trad['nomEn']])
                            );
                        },
                        []
                    )
                );
            },
            []
        );
        // Remove accents from data and criteria
        $data     = array_map(function($d){ return \App\Utils\StringHelper::removeAccents($d); }, $data);
        $criteria = array_map(function($d){ return \App\Utils\StringHelper::removeAccents($d); }, $criteria);

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