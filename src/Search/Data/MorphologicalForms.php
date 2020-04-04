<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class MorphologicalForms implements CriteriaDataInterface
{

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $values = [
            "Nominatif"   => ($locale == "fr" ? "Nominatif" : "Nominative"),
            "Vocatif"     => ($locale == "fr" ? "Vocatif" : "Vocative"),
            "Accusatif"   => ($locale == "fr" ? "Accusatif" : "Accusative"),
            "Génitif"     => ($locale == "fr" ? "Génitif" : "Genitive"),
            "Datif"       => ($locale == "fr" ? "Datif" : "Dative"),
            "Ablatif"     => ($locale == "fr" ? "Ablatif" : "Ablative"),
            "Indéterminé" => ($locale == "fr" ? "Indéterminé" : "Undetermined"),
        ];
        return $values;
    }

    public static function getCacheTags(): array
    {
        return [];
    }

    public static function getCacheLifetime(): int
    {
        return 3600 * 24 * 30; // 30 days
    }
}
