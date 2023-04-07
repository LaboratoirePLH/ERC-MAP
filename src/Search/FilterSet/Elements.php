<?php

namespace App\Search\FilterSet;

class Elements extends AbstractFilterSet
{
    public function filter(array $criteria): array
    {
        $filtered = [];

        // Only examine data of the requested type
        $this->data = array_filter($this->data, function ($e) {
            return strtolower($e->getEntite()) === 'attestation';
        });


        return array_filter($this->data, function ($e) use ($criteria) {
            if (array_key_exists('languages', $criteria) && !empty($criteria['languages'])) {
                $crit = [[
                    "values" => $criteria['languages'],
                    "mode"   => ($criteria['languages_mode'] ?? "one")
                ]];
                if (!\App\Search\Filter\Languages::filter($e, $crit, $this->sortedData)) {
                    return false;
                }
            }
            if (array_key_exists('datation', $criteria) && (
                (array_key_exists('post_quem', $criteria['datation']) && is_numeric($criteria['datation']['post_quem']))
                || (array_key_exists('ante_quem', $criteria['datation']) && is_numeric($criteria['datation']['ante_quem'])))) {
                // We need at least one numeric value (empty field will set empty string) to filter by datation
                if (!\App\Search\Filter\Datation::filter($e, [$criteria['datation']], $this->sortedData)) {
                    return false;
                }
            }
            if (array_key_exists('locations', $criteria) && !empty($criteria['locations'])) {
                $crit = [[
                    "values" => $criteria['locations'],
                    "mode"   => "one"
                ]];
                if (!\App\Search\Filter\Locations::filter($e, $crit, $this->sortedData)) {
                    return false;
                }
            }
            if (array_key_exists('sourceTypes', $criteria) && !empty($criteria['sourceTypes'])) {
                $crit = [[
                    "values" => $criteria['sourceTypes'],
                    "mode"   => "one"
                ]];
                if (!\App\Search\Filter\SourceTypes::filter($e, $crit, $this->sortedData)) {
                    return false;
                }
            }
            if (array_key_exists('element_count', $criteria)) {
                if (!\App\Search\Filter\ElementCount::filter($e, [$criteria['element_count']], $this->sortedData)) {
                    return false;
                }
            }
            if (array_key_exists('element_position', $criteria)) {
                if (!\App\Search\Filter\ElementPosition::filter($e, $criteria['element_position'], $this->sortedData)) {
                    return false;
                }
            }
            if (array_key_exists('divine_powers_count', $criteria)) {
                if (!\App\Search\Filter\DivinePowersCount::filter($e, [$criteria['divine_powers_count']], $this->sortedData)) {
                    return false;
                }
            }
            if (array_key_exists('formule', $criteria)) {
                $crit = [[
                    "values" => $criteria['formule'],
                    "mode"   => ($criteria['formules_mode'] ?? "one")
                ]];
                if (!\App\Search\Filter\Formula::filter($e, $crit, $this->sortedData)) {
                    return false;
                }
            }

            return true;
        });
    }
}
