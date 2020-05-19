<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class ElementNumbers implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial ne.{id, {$nameField}} FROM \App\Entity\NombreElement ne"
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
        return ['NombreElement'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24*30; // 30 days
    }
}