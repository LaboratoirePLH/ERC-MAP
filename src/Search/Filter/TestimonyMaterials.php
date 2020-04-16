<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class TestimonyMaterials extends AbstractFilter
{

    public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool
    {
        self::validateInput($entity, $criteria, $sortedData);

        // We resolve the attestations
        $attestations = self::toArray(
            self::resolveAttestations($entity, $sortedData)
        );
        // For each attestation, we get its materials, and merge it in a single-level array (removing duplicates)
        $data = array_column($attestations, 'materiels');
        if (!empty($data)) {
            $data = array_reduce(
                array_merge(
                    ...$data
                ),
                function ($result, $item) {
                    foreach ($result as $r) {
                        if ((($r['categorieMateriel'] ?? null)['id'] ?? null) == (($item['categorieMateriel'] ?? null)['id'] ?? null)
                            && (($r['materiel'] ?? null)['id'] ?? null) == (($item['materiel'] ?? null)['id'] ?? null)
                        ) {
                            return $result;
                        }
                    }
                    return array_merge($result, [$item]);
                },
                []
            );
        }

        // For each criteria entry, we will get a boolean result of whether the entry is valid against the data
        // We need at least one truthy value to accept the data
        return !!count(array_filter(array_map(function ($crit) use ($data) {
            $requireAll = ($crit['mode'] ?? 'one') === 'all';
            // Each criteria entry value is a json encoded array
            $crit = array_map('json_decode', array_filter($crit['values']));

            // We count the matched criteria values
            $matched = 0;
            foreach ($crit as $c) {
                foreach ($data as $d) {
                    // Single ID is material category
                    if (array_key_exists('categorieMateriel', $d) && $c[0] == ($d['categorieMateriel']['id'] ?? null)) {
                        if (count($c) === 2) {
                            // Double ID is material category + material type
                            if (array_key_exists('materiel', $d) && $c[1] == $d['materiel']['id']) {
                                $matched++;
                                break; // Break the inner foreach since we found a material matching this criteria value
                            }
                        } else {
                            $matched++;
                            break; // Break the inner foreach since we found a material matching this criteria value
                        }
                    }
                }
            }

            // If we require all values to be matched we must find at least as many as the criteria values
            // Else we only need one
            return $matched >= ($requireAll ? count($crit) : 1);
        }, $criteria)));
    }
}
