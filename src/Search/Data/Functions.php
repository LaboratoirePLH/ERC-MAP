<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class Functions implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial qf.{id, {$nameField}} FROM \App\Entity\QFonction qf"
        );
        $functions = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($functions, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $functions;
    }

    public static function getCacheTags(): array
    {
        return ['QFonction'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24*30; // 30 days
    }
}