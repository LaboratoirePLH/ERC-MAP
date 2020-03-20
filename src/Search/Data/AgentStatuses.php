<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class AgentStatuses implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial sa.{id, {$nameField}} FROM \App\Entity\StatutAffiche sa"
        );
        $agentStatuses = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($agentStatuses, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $agentStatuses;
    }

    public static function getCacheTags(): array
    {
        return ['StatutAffiche'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}