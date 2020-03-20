<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class TestimonyMaterials implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial c.{id, {$nameField}} FROM \App\Entity\CategorieMateriel c"
        );
        $categorieMateriel = $query->getArrayResult();
        $query = $entityManager->createQuery(
            "SELECT partial m.{id, {$nameField}}, partial cm.{id, {$nameField}}
             FROM \App\Entity\Materiel m LEFT JOIN m.categorieMateriel cm"
        );
        $materiels = $query->getArrayResult();
        $testimonyMaterials = [];
        foreach($categorieMateriel as $cm){
            $id = json_encode([$cm['id']]);
            $testimonyMaterials[$id] = $cm[$nameField];
        }
        foreach($materiels as $m){
            $id = json_encode([$m['categorieMateriel']['id'], $m['id']]);
            $testimonyMaterials[$id] = $m['categorieMateriel'][$nameField].' > '.$m[$nameField];
        }
        uasort($testimonyMaterials, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $testimonyMaterials;
    }

    public static function getCacheTags(): array
    {
        return ['CategorieMateriel', 'Materiel'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}