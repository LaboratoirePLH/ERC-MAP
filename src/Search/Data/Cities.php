<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class Cities implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $query = $entityManager->createQuery(
            "SELECT partial l.{id, pleiadesVille, nomVille} FROM \App\Entity\Localisation l WHERE l.nomVille IS NOT NULL"
        );
        $lieux = $query->getArrayResult();
        $locations = [];

        foreach($lieux as $l){
            $id = $l['pleiadesVille'] ?? $l['nomVille'];
            $value = $l['nomVille'] . ($l['pleiadesVille'] ? ' ('.$l['pleiadesVille'].')' : '');
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
        return ['Localisation'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}