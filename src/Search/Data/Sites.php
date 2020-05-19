<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class Sites implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $query = $entityManager->createQuery(
            "SELECT partial l.{id, pleiadesSite, nomSite} FROM \App\Entity\Localisation l WHERE l.nomSite IS NOT NULL"
        );
        $lieux = $query->getArrayResult();
        $locations = [];

        foreach($lieux as $l){
            $id = $l['pleiadesSite'] ?? $l['nomSite'];
            $value = $l['nomSite'] . ($l['pleiadesSite'] ? ' ('.$l['pleiadesSite'].')' : '');
            $locations[$id] = $value;
        }
        uasort($locations, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $locations;
    }

    public static function getCacheTags(): array
    {
        return ['Localisation'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}