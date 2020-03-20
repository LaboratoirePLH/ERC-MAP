<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class PoliticalEntities implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "affichage" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial ep.{id, {$nameField}} FROM \App\Entity\EntitePolitique ep"
        );
        $politicalEntities = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($politicalEntities, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $politicalEntities;
    }

    public static function getCacheTags(): array
    {
        return ['EntitePolitique'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24*30; // 30 days
    }
}