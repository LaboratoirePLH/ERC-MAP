<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class TestimonyActs implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial p.{id, {$nameField}} FROM \App\Entity\Pratique p"
        );
        $testimonyActs = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($testimonyActs, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $testimonyActs;
    }

    public static function getCacheTags(): array
    {
        return ['Pratique'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24*30; // 30 days
    }
}