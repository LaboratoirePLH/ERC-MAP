<?php

namespace App\Search\Data;

use \Doctrine\ORM\EntityManager;

class Names implements CriteriaDataInterface
{

    public static function compute(EntityManager $entityManager, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $query = $entityManager->createQuery(
            "SELECT partial e.{id, etatAbsolu, betaCode}, partial t.{id, {$nameField}}
             FROM \App\Entity\Element e LEFT JOIN e.traductions t"
        );
        $els = $query->getArrayResult();
        $elements = [];
        foreach ($els as $el) {
            $trads = array_column($el['traductions'], $nameField);
            if (!empty($trads)) {
                $trads = '(' . implode(' ; ', $trads) . ')';
            } else {
                $trads = '';
            }
            $elements[$el['id']] = implode(' ', array_filter([
                $el['etatAbsolu'],
                $el['betaCode'] ? '[' . $el['betaCode'] . ']' : null,
                $trads
            ]));
        }
        uasort($elements, function ($a, $b) {
            return \App\Utils\StringHelper::removeAccents(strtolower(strip_tags($a)))
                <=> \App\Utils\StringHelper::removeAccents(strtolower(strip_tags($b)));
        });
        return $elements;
    }

    public static function getCacheTags(): array
    {
        return ['Element', 'TraductionElement'];
    }

    public static function getCacheLifetime(): int
    {
        return 3600 * 24; // 1 day
    }
}
