<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class ElementPosition extends AbstractFilter
{
    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We get all the resolved attestations
        $attestations = self::toArray(
            self::resolveAttestations($entity, $sortedData)
        );

        /*******************************************************/
        /*                       WARNING                       */
        /* This filter should only be used with attestations!  */
        /* Other types may have multiple attestations, causing */
        /*   unpredicted results                               */
        /*******************************************************/

        // For each attestation found, we check that given criteria are all true
        // We need at least one attestation (normal use case will only have one to test anyway) to be correct to include the result
        return !!count(array_filter(array_map(function ($attestation) use ($criteria) {
            foreach ($criteria as $crit) {
                $elementId              = intval($crit['id']);
                $elementPosition        = $crit['position'];
                $elementFound           = false;
                $elementPositionMatches = false;

                foreach (($attestation['elements'] ?? []) as $ce) {
                    if ($ce['element']['id'] === $elementId) {
                        $elementFound = true;

                        switch ($elementPosition) {
                            case 'start':
                                $elementPositionMatches = ($ce['positionElement'] == 1);
                                break;
                            case 'end':
                                $elementPositionMatches = ($ce['positionElement'] == count($attestation['elements']));
                                break;
                            case 'other':
                                $elementPositionMatches = ($ce['positionElement'] > 1) && ($ce['positionElement'] < count($attestation['elements']));
                                break;
                            case 'any':
                            default:
                                $elementPositionMatches = true;
                                break;
                        }
                        // We have found the element at a correct position, no need to go further
                        if ($elementPositionMatches) {
                            break;
                        }
                    }
                }

                if (!$elementFound || !$elementPositionMatches) {
                    // No need to go further, we have a criteria not met
                    return false;
                }
            }
            // If we get here, all criteria have been met
            return true;
        }, $attestations)));
    }
}
