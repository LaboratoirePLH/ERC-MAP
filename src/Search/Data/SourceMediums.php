<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class SourceMediums implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial c.{id, {$nameField}} FROM \App\Entity\CategorieSupport c"
        );
        $categorieSupport = $query->getArrayResult();
        $query = $entityManager->createQuery(
            "SELECT partial t.{id, {$nameField}}, partial c.{id, {$nameField}}
             FROM \App\Entity\TypeSupport t LEFT JOIN t.categorieSupport c"
        );
        $typeSupport = $query->getArrayResult();
        $sourceMediums = [];
        foreach($categorieSupport as $cs){
            $id = json_encode([$cs['id']]);
            $sourceMediums[$id] = $cs[$nameField];
        }
        foreach($typeSupport as $ts){
            $id = json_encode([$ts['categorieSupport']['id'], $ts['id']]);
            $sourceMediums[$id] = $ts['categorieSupport'][$nameField].' > '.$ts[$nameField];
        }
        uasort($sourceMediums, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $sourceMediums;
    }

    public static function getCacheTags(): array
    {
        return ['CategorieSupport', 'TypeSupport'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}