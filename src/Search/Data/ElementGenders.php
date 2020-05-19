<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class ElementGenders implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial ge.{id, {$nameField}} FROM \App\Entity\GenreElement ge"
        );
        $genders = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($genders, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $genders;
    }

    public static function getCacheTags(): array
    {
        return ['GenreElement'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24*30; // 30 days
    }
}