<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class AgentGenders implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial g.{id, {$nameField}} FROM \App\Entity\Genre g"
        );
        $agentGenders = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($agentGenders, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $agentGenders;
    }

    public static function getCacheTags(): array
    {
        return ['Genre'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}