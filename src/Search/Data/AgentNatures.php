<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class AgentNatures implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial g.{id, {$nameField}} FROM \App\Entity\Nature g"
        );
        $agentNatures = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($agentNatures, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $agentNatures;
    }

    public static function getCacheTags(): array
    {
        return ['Nature'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}