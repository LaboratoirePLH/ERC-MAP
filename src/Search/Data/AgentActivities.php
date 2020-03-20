<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class AgentActivities implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial aa.{id, {$nameField}} FROM \App\Entity\ActiviteAgent aa"
        );
        $agentActivities = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($agentActivities, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $agentActivities;
    }

    public static function getCacheTags(): array
    {
        return ['ActiviteAgent'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}