<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class AbsoluteForms implements CriteriaDataInterface {

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial e.{id, etatAbsolu, betaCode}
             FROM \App\Entity\Element e"
        );
        $els = $query->getArrayResult();
        $elements = [];
        foreach($els as $el){
            $elements[$el['id']] = implode(' ', array_filter([
                $el['etatAbsolu'],
                $el['betaCode'] ? '['.$el['betaCode'].']' : null
            ]));
        }
        uasort($elements, function($a, $b){
            return \App\Utils\StringHelper::removeAccents(strip_tags($a))
                <=> \App\Utils\StringHelper::removeAccents(strip_tags($b));
        });
        return $elements;
    }

    public static function getCacheTags(): array
    {
        return ['Element'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600*24; // 1 day
    }
}