<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

interface CriteriaDataInterface {
    public static function compute(EntityManager $entityManager, string $locale): array;

    public static function getCacheTags(): array;

    public static function getCacheLifetime(): int;
}