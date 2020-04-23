<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class CitiesSites implements CriteriaDataInterface
{

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $cities = Cities::compute($entityManager, $locale);
        $sites = Sites::compute($entityManager, $locale);

        $locations = [];
        foreach ($cities as $key => $value) {
            $id = json_encode(['city', $key]);
            $locations[$id] = $value;
        }
        foreach ($sites as $key => $value) {
            $id = json_encode(['site', $key]);
            $locations[$id] = $value;
        }

        uasort($locations, function ($a, $b) {
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $locations;

        return $locations;
    }

    public static function getCacheTags(): array
    {
        return ['Localisation'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600 * 24; // 1 day
    }
}
