<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class SourceMaterials implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial c.{id, {$nameField}} FROM \App\Entity\CategorieMateriau c"
        );
        $categorieMateriau = $query->getArrayResult();
        $query = $entityManager->createQuery(
            "SELECT partial m.{id, {$nameField}}, partial cm.{id, {$nameField}}
             FROM \App\Entity\Materiau m LEFT JOIN m.categorieMateriau cm"
        );
        $materiaux = $query->getArrayResult();
        $sourceMaterials = [];
        foreach($categorieMateriau as $cm){
            $id = json_encode([$cm['id']]);
            $sourceMaterials[$id] = $cm[$nameField];
        }
        foreach($materiaux as $m){
            $id = json_encode([$m['categorieMateriau']['id'], $m['id']]);
            $sourceMaterials[$id] = $m['categorieMateriau'][$nameField].' > '.$m[$nameField];
        }
        uasort($sourceMaterials, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $sourceMaterials;
    }

    public static function getCacheTags(): array
    {
        return ['CategorieMateriau', 'Materiau'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}