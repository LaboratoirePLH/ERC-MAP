<?php

namespace App\Search\FilterSet;

use App\Entity\IndexRecherche;

class Advanced extends AbstractFilterSet
{
    public function filter(array $criteria): array
    {
        $filtered = [];

        $resultsType = $criteria['resultsType'];
        unset($criteria['resultsType']);

        // Ensure comments criteria is at the end to improve performance
        if (array_key_exists('comments', $criteria)) {
            $commentsCriteria = $criteria['comments'];
            unset($criteria['comments']);
            $criteria['comments'] = $commentsCriteria;
        }
        // Ensure freeText criteria is at the end to improve performance
        if (array_key_exists('freeText', $criteria)) {
            $freeTextCriteria = $criteria['freeText'];
            unset($criteria['freeText']);
            $criteria['freeText'] = $freeTextCriteria;
        }

        foreach ($this->data as $e) {

            // Only examine data of the requested type
            if (strtolower($e->getEntite()) != $resultsType) {
                continue;
            }

            foreach ($criteria as $criteriaName => $criteriaValues) {
                // Compute fully qualified classname from criteria name
                $cls = '\\App\\Search\\Filter\\' . ucfirst($criteriaName);

                // If class is not found, return empty array with default lifetime and default tag
                if (!class_exists($cls)) {
                    throw new \InvalidArgumentException("Could not find class '$cls' to process criteria $criteriaName");
                }

                // Split criteriaValues between inclusive and exclusive
                $inclusiveCriteriaValues = [];
                $exclusiveCriteriaValues = [];
                foreach ($criteriaValues as $cv) {
                    $type = 'inclusive';
                    if (array_key_exists('type', $cv)) {
                        $type = $cv['type'];
                        unset($cv['type']);
                    }
                    if ($type === 'inclusive') {
                        $inclusiveCriteriaValues[] = $cv;
                    } else if ($type === 'exclusive') {
                        $exclusiveCriteriaValues[] = $cv;
                    } else {
                        throw new \InvalidArgumentException("Invalid type value '$cls' for criteria $criteriaName");
                    }
                }
                $inclusiveCriteriaValues = array_filter($criteriaValues, function ($v) {
                    return ($v['type'] ?? 'inclusive') === 'inclusive';
                });
                $exclusiveCriteriaValues = array_filter($criteriaValues, function ($v) {
                    return ($v['type'] ?? 'inclusive') === 'exclusive';
                });

                if (count($inclusiveCriteriaValues)) {
                    $inclusiveCriteriaAccepted = $cls::filter($e, $inclusiveCriteriaValues, $this->sortedData);
                    if (!$inclusiveCriteriaAccepted) {
                        continue 2; // Ignore current record, because it did not match the inclusive filter
                    }
                }
                if (count($exclusiveCriteriaValues)) {
                    $exclusiveCriteriaAccepted = $cls::filter($e, $exclusiveCriteriaValues, $this->sortedData);
                    if ($exclusiveCriteriaAccepted) {
                        continue 2; // Ignore current record, because it did match the exclusive filter
                    }
                }
            }

            // We only get here if all criteria matched
            $filtered[] = $e;
        }
        return $filtered;
    }
}
