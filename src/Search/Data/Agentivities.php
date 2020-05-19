<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class Agentivities implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial ag.{id, {$nameField}} FROM \App\Entity\Agentivite ag"
        );
        $agentivities = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_column($query->getArrayResult(), $nameField)
        );
        uasort($agentivities, function($a, $b){
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $agentivities;
    }

    public static function getCacheTags(): array
    {
        return ['Agentivite'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}