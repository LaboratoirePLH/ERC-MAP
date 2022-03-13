<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class SourceBibliography extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved sources
        $sources = self::toArray(
            self::resolveSources($entity, $sortedData)
        );
        // For each source, we get its biligraphic references IDs
        $data = array_map(function ($s) {
            return array_column($s['sourceBiblios'] ?? [], 'id');
        }, $sources);

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            $crit = array_filter($crit['values']);

            foreach ($data as $d) {
                // $d contains all the bibliographic references of a source
                // If we require all values to be matched we must find at least as many as the criteria values
                // Else we only need one
                if (count(array_intersect($crit, $d)) >= ($requireAll ? count($crit) : 1)) {
                    return true;
                }
            }
        }, $criteria)));
    }
}
