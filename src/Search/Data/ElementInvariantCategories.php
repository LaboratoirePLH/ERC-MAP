<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class ElementInvariantCategories implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial ce.{id, {$nameField}} FROM \App\Entity\CategorieElement ce"
        );
        $numbers = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($numbers, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $numbers;
    }

    public static function getCacheTags(): array
    {
        return ['CategorieElement'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24*30; // 30 days
    }
}