<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class FreeText extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        $data = $entity->getTextData();

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data) {
            $strict = ($crit['mode'] ?? 'loose') === 'strict';
            $crit = $crit['value'];
            // $crit = self::cleanStringValue($crit['value']);
            // We require the criteria value to be present in data
            return !!count(array_filter($data, function ($d) use ($crit, $strict) {
                // If strict, we try to match with a regex with \b
                return $strict ? preg_match('/\b' . $crit . '\b/', $d) === 1 : (stristr($d, $crit) !== false);
            }));
        }, $criteria)));
    }
}
