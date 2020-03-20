<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class Regions implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial r.{id, {$nameField}} FROM \App\Entity\GrandeRegion r"
        );
        $grandeRegions = $query->getArrayResult();
        $query = $entityManager->createQuery(
            "SELECT partial r.{id, {$nameField}}, partial gr.{id, {$nameField}}
             FROM \App\Entity\SousRegion r LEFT JOIN r.grandeRegion gr"
        );
        $sousRegions = $query->getArrayResult();
        $regions = [];
        foreach($grandeRegions as $gr){
            $id = json_encode([$gr['id']]);
            $regions[$id] = $gr[$nameField];
        }
        foreach($sousRegions as $sr){
            $id = json_encode([$sr['grandeRegion']['id'], $sr['id']]);
            $regions[$id] = $sr['grandeRegion'][$nameField].' > '.$sr[$nameField];
        }
        uasort($regions, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $regions;
    }

    public static function getCacheTags(): array
    {
        return ['GrandeRegion', 'SousRegion'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}