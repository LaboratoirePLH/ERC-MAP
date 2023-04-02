<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

abstract class AbstractFilter
{

    /**
     * Filter function.
     *
     * @param IndexRecherche $entity Entity from the search index.
     * @param array $criteria Criteria data from the search form.
     * @param array $sortedData Full search index data sorted by entity type.
     *
     * @return bool
     **/
    abstract public static function filter(IndexRecherche $entity, array $criteria, array $sortedData): bool;

    public static function validateInput(IndexRecherche $e, array $criteria, array $sortedData)
    {
        if ($e->getEntite() != "Source" && $e->getEntite() != "Attestation" && $e->getEntite() != "Element") {
            throw new \InvalidArgumentException("Can only filter entities of class Source, Attestation or Element");
        }
        if (!is_array($criteria)) {
            throw new \InvalidArgumentException("Criteria values must be an array");
        }
        if (!is_array($sortedData) || !!count(array_diff(['sources', 'attestations', 'elements'], array_keys($sortedData)))) {
            throw new \InvalidArgumentException("Sorted data must be an array with keys 'sources', 'attestations' and 'elements'");
        }
    }

    public static function toArray(array $entities): array
    {
        return array_filter(array_map(function ($e) {
            if (is_array($e)) {
                return $e;
            } else if ($e instanceof IndexRecherche) {
                return $e->getData();
            } else {
                return null;
            }
        }, $entities));
    }

    public static function resolveSources(IndexRecherche $e, array $sortedData): array
    {
        $eData = $e->getData();
        if ($e->getEntite() === 'Element') {
            // Resolve all attestations, then call resolveSources on each of them
            return array_filter(array_reduce(
                self::resolveAttestations($e, $sortedData),
                function ($result, $attestation) use ($sortedData) {
                    return array_merge($result, self::resolveSources($attestation, $sortedData));
                },
                []
            ));
        } else if ($e->getEntite() === 'Attestation') {
            return array_filter([$sortedData['sources'][$eData['source']] ?? null]);
        } else if ($e->getEntite() === 'Source') {
            // Return itself
            return [$e];
        }
        return [];
    }

    public static function resolveAttestations(IndexRecherche $e, array $sortedData): array
    {
        $eData = $e->getData();
        if ($e->getEntite() === 'Element') {
            // Get all attestations that contain the element ID in their 'elementIds' property
            $elementId = $e->getId();
            return array_filter($sortedData['attestations'], function ($attestation) use ($elementId) {
                return in_array($elementId, $attestation->getData()['elementIds'] ?? []);
            });
        } else if ($e->getEntite() === 'Attestation') {
            // Return itself
            return [$e];
        } else if ($e->getEntite() === 'Source') {
            // Get all attestations whose ID is in the 'attestations' property
            return array_filter(array_map(function ($attestationId) use ($sortedData) {
                return $sortedData['attestations'][$attestationId] ?? null;
            }, $eData['attestations'] ?? []));
        }
        return [];
    }

    public static function resolveElements(IndexRecherche $e, array $sortedData, bool $allowIndirect = false): array
    {
        $eData = $e->getData();
        if ($e->getEntite() === 'Element') {
            $indirect = [];
            if ($allowIndirect) {
                $indirect = array_filter($sortedData['elements'], function ($element) use ($eData) {
                    return in_array($element->getId(), array_merge(
                        $eData['theonymesImplicites'] ?? [],
                        $eData['theonymesConstruits'] ?? []
                    ));
                });
            }
            // Return itself
            return array_merge([$e], $indirect);
        } else if ($e->getEntite() === 'Attestation') {
            // Get all elements whose ID is in the 'elementIds' property
            return array_filter(
                array_reduce(
                    ($eData['elementIds'] ?? []),
                    function ($result, $elementId) use ($sortedData, $allowIndirect) {
                        $element = $sortedData['elements'][$elementId] ?? null;
                        if ($element !== null) {
                            return array_merge($result, self::resolveElements($element, $sortedData, $allowIndirect));
                        }
                        return $result;
                    },
                    []
                )
            );
        } else if ($e->getEntite() === 'Source') {
            // Resolve all attestations, then call resolveElements on each of them
            return array_reduce(
                self::resolveAttestations($e, $sortedData),
                function ($result, $attestation) use ($sortedData, $allowIndirect) {
                    return array_merge($result, self::resolveElements($attestation, $sortedData, $allowIndirect));
                },
                []
            );
        }
        return [];
    }

    public static function resolveDatations(IndexRecherche $e, array $sortedData): array
    {
        $eData = $e->getData();
        if ($e->getEntite() === 'Element') {
            $attestations = self::resolveAttestations($e, $sortedData);
            return array_filter(array_reduce(
                $attestations,
                function ($result, $attestation) use ($sortedData, $e) {
                    return array_merge($result, self::resolveDatations($attestation, $sortedData));
                },
                []
            ));
        } else if ($e->getEntite() === 'Attestation') {
            $datation = $eData['datation'] ?? null;

            // Consider that a datation missing both postQuem and anteQuem is non-existent
            if (is_null($datation['postQuem'] ?? null) && is_null($datation['anteQuem'] ?? null)) {
                $datation = null;
            }

            return array_filter(array_merge([$datation], array_reduce(
                self::resolveSources($e, $sortedData),
                function ($result, $source) use ($sortedData) {
                    return array_merge($result, self::resolveDatations($source, $sortedData));
                },
                []
            )));
        } else if ($e->getEntite() === 'Source') {
            return array_filter([$eData['datation'] ?? null]);
        }
        return [];
    }

    public static function resolveLocalisations(IndexRecherche $e, array $sortedData, bool $allowIndirect = true): array
    {
        $eData = $e->getData();
        if ($e->getEntite() === 'Element') {

            return array_filter(array_reduce(
                $allowIndirect ? self::resolveAttestations($e, $sortedData) : [],
                function ($result, $attestation) use ($sortedData) {
                    return array_merge($result, self::resolveLocalisations($attestation, $sortedData));
                },
                [$eData['localisation'] ?? null]
            ));
        } else if ($e->getEntite() === 'Attestation') {
            // Get own localisation and the ones from the sources
            return array_filter(array_reduce(
                $allowIndirect ? self::resolveSources($e, $sortedData) : [],
                function ($result, $source) use ($sortedData) {
                    return array_merge($result, self::resolveLocalisations($source, $sortedData));
                },
                array_merge(
                    [$eData['localisation'] ?? null],
                    array_map(
                        function ($agent) {
                            return $agent['localisation'] ?? [];
                        },
                        self::resolveAgents($e, $sortedData)
                    )
                )
            ));
        } else if ($e->getEntite() === 'Source') {
            return array_filter([
                $eData['lieuDecouverte'] ?? null,
                $eData['lieuOrigine'] ?? null
            ]);
        }
        return [];
    }

    public static function resolveAgents(IndexRecherche $e, array $sortedData): array
    {
        // Resolve attestations and get agent data from them
        return array_filter(array_reduce(
            self::resolveAttestations($e, $sortedData),
            function ($result, $attestation) use ($sortedData) {
                return array_merge($result, $attestation->getData()['agents'] ?? []);
            },
            []
        ));
        return [];
    }

    public static function evaluateOperation($value, $operator, $criteria): bool
    {
        $value = intval($value);
        $criteria = intval($criteria);
        switch ($operator) {
            case "eq":
                return $value == $criteria;
            case "neq":
                return $value != $criteria;
            case "lt":
                return $value < $criteria;
            case "lte":
                return $value <= $criteria;
            case "gt":
                return $value > $criteria;
            case "gte":
                return $value >= $criteria;
        }
        return false;
    }

    protected static function cleanStringValue(string $str): string
    {
        // First replace <br/> tags by newliens to keep the word boundaries
        $str = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $str);

        return mb_strtolower(\App\Utils\StringHelper::removeAccents(strip_tags($str)));
    }
}
