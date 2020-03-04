<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class SourceTypes implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial c.{id, {$nameField}} FROM \App\Entity\CategorieSource c"
        );
        $categorieSource = $query->getArrayResult();
        $query = $entityManager->createQuery(
            "SELECT partial t.{id, {$nameField}}, partial c.{id, {$nameField}}
             FROM \App\Entity\TypeSource t LEFT JOIN t.categorieSource c"
        );
        $typeSource = $query->getArrayResult();
        $sourceTypes = [];
        foreach($categorieSource as $cs){
            $id = json_encode([$cs['id']]);
            $sourceTypes[$id] = $cs[$nameField];
        }
        foreach($typeSource as $ts){
            $id = json_encode([$ts['categorieSource']['id'], $ts['id']]);
            $sourceTypes[$id] = $ts['categorieSource'][$nameField].' > '.$ts[$nameField];
        }
        uasort($sourceTypes, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $sourceTypes;
    }

    public static function getCacheTags(): array
    {
        return ['categorie_source', 'type_source'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}