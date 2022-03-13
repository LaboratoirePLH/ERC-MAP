<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class SourceBibliography implements CriteriaDataInterface
{

    public static function compute(EntityManager $entityManager, string $locale): array
    {

        $query = $entityManager->createQuery(
            "SELECT partial b.{id, titreAbrege} FROM \App\Entity\Biblio b
                WHERE b.titreAbrege IS NOT NULL"
        );
        $result = $query->getArrayResult();
        $biblios = array_combine(
            array_column($result, 'id'),
            array_column($result, 'titreAbrege')
        );
        uasort($biblios, function ($a, $b) {
            return \App\Utils\StringHelper::removeAccents($a)
                <=> \App\Utils\StringHelper::removeAccents($b);
        });
        return $biblios;
    }

    public static function getCacheTags(): array
    {
        return ['Auteur'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600 * 24 * 30; // 30 days
    }
}
