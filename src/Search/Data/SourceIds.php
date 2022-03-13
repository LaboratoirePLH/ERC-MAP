<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class SourceIds implements CriteriaDataInterface
{

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $query = $entityManager->createQuery(
            "SELECT partial s.{id}
             FROM \App\Entity\Source s"
        );
        $ids = array_column($query->getArrayResult(), 'id');
        $values = array_map(function ($id) {
            return "#" . $id;
        }, $ids);
        $data = array_combine($ids, $values);
        ksort($data, SORT_NUMERIC);
        return $data;
    }

    public static function getCacheTags(): array
    {
        return ['Source'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600 * 24; // 1 day
    }
}
