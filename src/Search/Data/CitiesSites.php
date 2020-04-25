<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class CitiesSites implements CriteriaDataInterface
{

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $query = $entityManager->createQuery(
            "SELECT partial l.{id, pleiadesVille, nomVille, pleiadesSite, nomSite} FROM \App\Entity\Localisation l WHERE l.nomVille IS NOT NULL OR l.nomSite IS NOT NULL"
        );

        $lieux = $query->getArrayResult();
        $locations = [];

        foreach ($lieux as $l) {
            $cityId = null;
            $cityName = "";
            if (strlen($l['nomVille'] ?? '') > 0) {
                $cityId = $l['pleiadesVille'] ?? $l['nomVille'];
                $cityName = $l['nomVille'] . ($l['pleiadesVille'] ? ' (' . $l['pleiadesVille'] . ')' : '');
                $locations[json_encode([$cityId])] = trim($cityName);
            }
            if (strlen($l['nomSite'] ?? '') > 0) {
                $siteId = $l['pleiadesSite'] ?? $l['nomSite'];
                $siteName = $l['nomSite'] . ($l['pleiadesSite'] ? ' (' . $l['pleiadesSite'] . ')' : '');
                $locations[json_encode([$cityId, $siteId])] = trim($cityName . ' > ' . $siteName);
            }
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
