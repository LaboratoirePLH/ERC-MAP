<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class TestimonyOccasions implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial c.{id, {$nameField}} FROM \App\Entity\CategorieOccasion c"
        );
        $categorieOccasion = $query->getArrayResult();
        $query = $entityManager->createQuery(
            "SELECT partial o.{id, {$nameField}}, partial co.{id, {$nameField}}
             FROM \App\Entity\Occasion o LEFT JOIN o.categorieOccasion co"
        );
        $occasions = $query->getArrayResult();
        $testimonyOccasions = [];
        foreach($categorieOccasion as $co){
            $id = json_encode([$co['id']]);
            $testimonyOccasions[$id] = $co[$nameField];
        }
        foreach($occasions as $o){
            $id = json_encode([$o['categorieOccasion']['id'], $o['id']]);
            $testimonyOccasions[$id] = $o['categorieOccasion'][$nameField].' > '.$o[$nameField];
        }
        uasort($testimonyOccasions, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $testimonyOccasions;
    }

    public static function getCacheTags(): array
    {
        return ['CategorieOccasion', 'Occasion'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}