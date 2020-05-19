<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class Locations implements CriteriaDataInterface {

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
        $query = $entityManager->createQuery(
            "SELECT partial l.{id, pleiadesVille, nomVille}, partial sr.{id, {$nameField}}, partial gr.{id, {$nameField}}
             FROM \App\Entity\Localisation l LEFT JOIN l.sousRegion sr LEFT JOIN l.grandeRegion gr
             WHERE l.nomVille IS NOT NULL"
        );
        $lieux = $query->getArrayResult();
        $locations = [];
        foreach($grandeRegions as $gr){
            $id = json_encode([$gr['id']]);
            $locations[$id] = $gr[$nameField];
        }
        foreach($sousRegions as $sr){
            $id = json_encode([$sr['grandeRegion']['id'], $sr['id']]);
            $locations[$id] = $sr['grandeRegion'][$nameField].' > '.$sr[$nameField];
        }
        foreach($lieux as $l){
            // TODO : Manage case where we have 2 nomVille with identical grandeRegion & sousRegion, but no pleiades ID
            $id = json_encode([
                $l['grandeRegion']['id'] ?? 0,
                $l['sousRegion']['id'] ?? 0,
                $l['pleiadesVille']
            ]);
            $value = ($l['grandeRegion'][$nameField] ?? '').' > '.($l['sousRegion'][$nameField] ?? '').' > '.$l['nomVille'];
            $value = str_replace('>  >', '>>', $value);
            $locations[$id] = $value;
        }
        uasort($locations, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $locations;
    }

    public static function getCacheTags(): array
    {
        return ['GrandeRegion', 'SousRegion', 'Localisation'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}