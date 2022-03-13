<?php

namespace App\Search\FilterSet;

class Guided extends AbstractFilterSet
{
    public function filter(array $criteria): array
    {
        $filtered = [];

        foreach ($this->data as $e) {
            if (array_key_exists('names', $criteria) && !empty($criteria['names'])) {
                $crit = [[
                    "values" => $criteria['names'],
                    "mode"   => ($criteria['names_mode'] ?? "one")
                ]];
                if (!\App\Search\Filter\Names::filter($e, $crit, $this->sortedData)) {
                    continue;
                }
            }
            if (array_key_exists('languages', $criteria) && !empty($criteria['languages'])) {
                $crit = [[
                    "values" => $criteria['languages'],
                    "mode"   => ($criteria['languages_mode'] ?? "one")
                ]];
                if (!\App\Search\Filter\Languages::filter($e, $crit, $this->sortedData)) {
                    continue;
                }
            }
            if (array_key_exists('datation', $criteria) && (
                (array_key_exists('post_quem', $criteria['datation']) && is_numeric($criteria['datation']['post_quem']))
                || (array_key_exists('ante_quem', $criteria['datation']) && is_numeric($criteria['datation']['ante_quem'])))) {
                // We need at least one numeric value (empty field will set empty string) to filter by datation
                if (!\App\Search\Filter\Datation::filter($e, [$criteria['datation']], $this->sortedData)) {
                    continue;
                }
            }
            if (array_key_exists('locations', $criteria) && !empty($criteria['locations'])) {
                $crit = [[
                    "values" => $criteria['locations'],
                    "mode"   => "one"
                ]];
                if (!\App\Search\Filter\Locations::filter($e, $crit, $this->sortedData)) {
                    continue;
                }
            }
            if (array_key_exists('sourceTypes', $criteria) && !empty($criteria['sourceTypes'])) {
                $crit = [[
                    "values" => $criteria['sourceTypes'],
                    "mode"   => "one"
                ]];
                if (!\App\Search\Filter\SourceTypes::filter($e, $crit, $this->sortedData)) {
                    continue;
                }
            }
            if (array_key_exists('agents', $criteria) && !empty($criteria['agents'])) {
                $crit = [[
                    "values" => $criteria['agents'],
                    "mode"   => "one"
                ]];
                if (!\App\Search\Filter\Agentivities::filter($e, $crit, $this->sortedData)) {
                    continue;
                }
            }
            if (array_key_exists('freeText', $criteria) && !empty($criteria['freeText'])) {
                $crit = [[
                    "value" => $criteria['freeText'],
                    "mode"  => "loose"
                ]];
                if (!\App\Search\Filter\FreeText::filter($e, $crit, $this->sortedData)) {
                    continue;
                }
            }

            $filtered[] = $e;
        }
        usort($filtered, function ($a, $b) {
            if ($a->getEntite() == "Source" && $b->getEntite() == "Attestation") {
                return -1;
            }
            if ($a->getEntite() == "Attestation" && $b->getEntite() == "Source") {
                return 1;
            }
            return $a->getId() <=> $b->getId();
        });

        return $filtered;
    }
}
