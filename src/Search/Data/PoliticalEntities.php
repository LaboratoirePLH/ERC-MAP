<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class PoliticalEntities implements CriteriaDataInterface
{

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT ep FROM \App\Entity\EntitePolitique ep"
        );
        $politicalEntities = array_combine(
            array_column($query->getArrayResult(), 'id'),
            array_map(function ($pe) use ($nameField) {
                return \sprintf("%s (IACP: %s)", $pe[$nameField], $pe['numeroIacp'] ?? "?");
            }, $query->getArrayResult())
        );
        uasort($politicalEntities, function ($a, $b) {
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $politicalEntities;
    }

    public static function getCacheTags(): array
    {
        return ['EntitePolitique'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600 * 24 * 30; // 30 days
    }
}
