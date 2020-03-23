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

        foreach($this->data as $e){

            // Only examine data of the requested type
            if(strtolower($e->getEntite()) != $resultsType) { continue; }

            foreach($criteria as $criteriaName => $criteriaValues){
                // Compute fully qualified classname from criteria name
                $cls = '\\App\\Search\\Filter\\' . ucfirst($criteriaName);

                // If class is not found, return empty array with default lifetime and default tag
                if(!class_exists($cls)){
                    throw new InvalidArgumentException("Could not find class '$cls' to process criteria $criteriaName");
                }

                $criteriaAccepted = $cls::filter($e, $criteriaValues, $this->sortedData);
                if(!$criteriaAccepted){
                    continue 2; // Ignore current record, because it did not match the filter
                }
            }

            // We only get here if all criteria matched
            $filtered[] = $e;
        }
        return $filtered;
    }
}
