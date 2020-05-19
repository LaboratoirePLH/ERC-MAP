<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class Topographies implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial qt.{id, {$nameField}} FROM \App\Entity\QTopographie qt"
        );
        $topographies = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($topographies, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $topographies;
    }

    public static function getCacheTags(): array
    {
        return ['QTopographie'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24*30; // 30 days
    }
}