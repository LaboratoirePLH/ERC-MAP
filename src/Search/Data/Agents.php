<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class Agents implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial aa.{id, {$nameField}} FROM \App\Entity\ActiviteAgent aa"
        );
        $activites = array_combine(
            array_map(function($a){ return json_encode(['activite', $a]); }, array_column($query->getArrayResult(), 'id')),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($activites, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });

        $query = $entityManager->createQuery(
            "SELECT partial ag.{id, {$nameField}} FROM \App\Entity\Agentivite ag"
        );
        $agentivites = array_combine(
            array_map(function($a){ return json_encode(['agentivite', $a]); }, array_column($query->getArrayResult(), 'id')),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($agentivites, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return [
            'activites'   => $activites,
            'agentivites' => $agentivites
        ];
    }

    public static function getCacheTags(): array
    {
        return ['ActiviteAgent', 'Agentivite'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}