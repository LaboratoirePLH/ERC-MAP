<?php

namespace App\Search\Decorator;

use App\Entity\IndexRecherche;
use Symfony\Contracts\Translation\TranslatorInterface;

class Advanced
{

    public static $translator;

    public static function decorate(array $data, array $allData, string $locale, TranslatorInterface $translator): array
    {
        self::$translator = $translator;
        $result = [];
        foreach ($data as $entity) {

            $entityData = $entity->getData();
            $method = "decorate" . $entity->getEntite();
            $result[] = self::$method($entity, $allData, $locale, $translator);
        }
        return $result;
    }

    protected static function decorateSource(IndexRecherche $entity, array $allData, string $locale, TranslatorInterface $translator): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $data = $entity->getData();

        $result = [
            "urlTexte"    => $data['urlTexte'] ?? '',
            "urlImage"    => $data['urlImage'] ?? '',
            "commentaire" => $data['commentaire' . ucfirst(strtolower($locale))] ?? '',
        ];

        $mainEdition = array_reduce(
            $data['sourceBiblios'],
            function ($carry, $item) {
                return ($item['editionPrincipale'] ?? false) ? $item : $carry;
            }
        );
        $result['reference'] = $mainEdition === null ? null
            : implode(' ', array_filter([
                $mainEdition['titreAbrege'] ?? null,
                $mainEdition['reference'] ?? null
            ]));


        foreach (['categorieSource', 'categorieMateriau', 'typeMateriau', 'categorieSupport', 'typeSupport', 'titrePrincipal'] as $manyToOneField) {
            $result[$manyToOneField] = array_key_exists($manyToOneField, $data) ? $data[$manyToOneField][$nameField] : '';
        }

        foreach (['typeSource', 'langues', 'auteurs'] as $manyToManyField) {
            if (array_key_exists($manyToManyField, $data)) {
                $result[$manyToManyField] = array_map(function ($item) use ($nameField) {
                    return $item[$nameField];
                }, $data[$manyToManyField]);
            } else {
                $result[$manyToManyField] = [];
            }
        }

        $result = array_merge($result, self::_decorateDatation($data['datation'] ?? []));
        $result = array_merge($result, self::_decorateLocalisation($data['lieuOrigine'] ?? $data['lieuDecouverte'] ?? [], $locale));

        $result['extraits'] = array_filter(array_map(
            function ($att) {
                return $att->getData()['extraitAvecRestitution'] ?? $att->getData()['translitteration'] ?? '';
            },
            array_filter(
                $allData,
                function ($e) use ($data) {
                    return $e->getEntite() == "Attestation" && in_array($e->getId(), $data['attestations'] ?? []);
                }
            )
        ));

        // Add link data
        $result['link'] = ['type' => strtolower($entity->getEntite()), 'id' => $entity->getId()];

        return ['source' => $result];
    }

    protected static function decorateAttestation(IndexRecherche $entity, array $allData, string $locale, TranslatorInterface $translator): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $data = $entity->getData();

        $result = [
            "etatFiche"              => $data['etatFiche' . ucfirst(strtolower($locale))] ?? '',
            "passage"                => $data['passage'] ?? '',
            "prose"                  => !is_null($data['prose'] ?? null) ? $translator->trans($data['prose'] ? 'generic.choices.yes' : 'generic.choices.no') : '',
            "poesie"                 => !is_null($data['poesie'] ?? null) ? $translator->trans($data['poesie'] ? 'generic.choices.yes' : 'generic.choices.no') : '',
            "extraitAvecRestitution" => $data['extraitAvecRestitution'] ?? '',
            "translitteration"       => $data['translitteration'] ?? '',
            "compteElement"          => count($data['elementIds'] ?? []),
            "fiabilite"              => array_key_exists('fiabilite', $data) ? self::$translator->trans('attestation.fiabilite.niveau_' . $data['fiabilite']) : '',
            "commentaire"            => $data['commentaire' . ucfirst(strtolower($locale))] ?? '',
        ];

        if (array_key_exists('traductions', $data)) {
            $result['traductions'] = array_slice(array_map(function ($item) use ($nameField) {
                return $item[$nameField] ?? '';
            }, $data['traductions']), 0, 1);
        } else {
            $result['traductions'] = [];
        }
        if (array_key_exists('pratiques', $data)) {
            $result['pratiques'] = array_map(function ($item) use ($nameField) {
                return $item[$nameField];
            }, $data['pratiques']);
        } else {
            $result['pratiques'] = [];
        }

        $result['occasions'] = array_map(function ($o) use ($nameField) {
            return ($o['categorieOccasion'][$nameField] ?? '') . ' > ' . ($o['occasion'][$nameField] ?? '');
        }, $data['occasions'] ?? []);

        $result['materiels'] = array_map(function ($o) use ($nameField) {
            return ($o['categorieMateriel'][$nameField] ?? '')
                . ' > '
                . ($o['materiel'][$nameField] ?? '')
                . '('
                . ($o['quantite'] ?? '?')
                . ')';
        }, $data['materiels'] ?? []);

        $result = array_merge($result, self::_decorateDatation($data['datation'] ?? []));
        $result = array_merge($result, self::_decorateLocalisation($data['localisation'] ?? [], $locale));

        $result['agents'] = array_filter(array_column($data['agents'] ?? [], 'designation'));

        if (array_key_exists('formule1', $data)) {
            $result = array_merge($result, $data['formule1']);
            if (!array_key_exists('puissancesDivines', $result) || $result['puissancesDivines'] == "") {
                $result['puissancesDivines'] = self::$translator->trans('generic.fields.indetermine');
            }
        } else {
            $result['formule'] = '';
            $result['puissancesDivines'] = '';
        }

        // Add linked testimonies
        $result['attestationsLiees'] = array_map(function ($id) {
            return ['type' => 'attestation', 'id' => $id];
        }, ($data['attestationsLiees'] ?? []));

        // Add link data
        $result['link'] = ['type' => strtolower($entity->getEntite()), 'id' => $entity->getId()];

        // Find source and get its data
        $sourceId = $data['source'];
        $source = array_reduce($allData, function ($result, $e) use ($sourceId) {
            return $result ?? (($e->getEntite() == "Source" && $e->getId() == $sourceId) ? $e : null);
        }, null);
        $sourceData = self::decorateSource($source, $allData, $locale, $translator);

        return array_merge($sourceData, ['attestation' => $result]);
    }

    protected static function decorateElement(IndexRecherche $entity, array $allData, string $locale, TranslatorInterface $translator): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $data = $entity->getData();

        $result = [
            "etatAbsolu"    => $data['etatAbsolu'] ?? '',
            "betaCode"      => $data['betaCode'] ?? '',
            "natureElement" => ($data['natureElement'] ?? [])[$nameField] ?? '',
            "commentaire"   => $data['commentaire' . ucfirst(strtolower($locale))] ?? '',
        ];

        if (array_key_exists('traductions', $data)) {
            $result['traductions'] = array_slice(array_map(function ($item) use ($nameField) {
                return $item[$nameField];
            }, $data['traductions']), 0, 1);
        } else {
            $result['traductions'] = [];
        }
        if (array_key_exists('categories', $data)) {
            $result['categories'] = array_map(function ($item) use ($nameField) {
                return $item[$nameField];
            }, $data['categories']);
        } else {
            $result['categories'] = [];
        }

        $result = array_merge($result, self::_decorateLocalisation($data['localisation'] ?? [], $locale));

        // Add usage data
        $attestations = array_filter(
            $allData,
            function ($a) use ($entity) {
                return $a->getEntite() == "Attestation" && in_array($entity->getId(), $a->getData()['elementIds'] ?? []);
            }
        );
        $sourcesIds = array_unique(array_map(function ($a) {
            return $a->getData()['source'];
        }, $attestations));

        if (count($sourcesIds) > 0) {
            $sources = array_filter(
                $allData,
                function ($a) use ($sourcesIds) {
                    return $a->getEntite() == "Source" && in_array($a->getId(), $sourcesIds);
                }
            );
        } else {
            $sources = [];
        }

        $localisations = array_unique(array_filter(array_map(function ($s) {
            return array_key_exists('lieuOrigine', $s->getData()) ? $s->getData()['lieuOrigine']['id'] : null;
        }, $sources)));

        $result['theonymesImplicites'] = array_values(array_map(
            function ($el) {
                return ['type' => 'element', 'id' => $el->getData()['id'], 'text' => $el->getData()['id'] . " " . $el->getData()['etatAbsolu']];
            },
            array_filter(
                $allData,
                function ($e) use ($data) {
                    return $e->getEntite() == "Element" && in_array($e->getId(), $data['theonymesImplicites'] ?? []);
                }
            )
        ));
        $result['theonymesConstruits'] = array_values(array_map(
            function ($el) {
                return ['type' => 'element', 'id' => $el->getData()['id'], 'text' => $el->getData()['id'] . " " . $el->getData()['etatAbsolu']];
            },
            array_filter(
                $allData,
                function ($e) use ($data) {
                    return $e->getEntite() == "Element" && in_array($e->getId(), $data['theonymesConstruits'] ?? []);
                }
            )
        ));

        $result['sources'] = count($sourcesIds);
        $result['attestations'] = count($attestations);
        $result['localisations'] = count($localisations);

        // Add link data
        $result['link'] = ['type' => strtolower($entity->getEntite()), 'id' => $entity->getId()];

        return ['element' => $result];
    }

    protected static function _decorateDatation(array $datation): array
    {
        return [
            'postQuem' => $datation['postQuem'] ?? null,
            'anteQuem' => $datation['anteQuem'] ?? null,
        ];
    }

    protected static function _decorateLocalisation(array $localisation, string $locale): array
    {
        return [
            'grandeRegion' => ($localisation['grandeRegion'] ?? [])['nom' . ucFirst($locale)] ?? '',
            'sousRegion'   => ($localisation['sousRegion'] ?? [])['nom' . ucFirst($locale)] ?? '',
            'ville'        => (($localisation['nomVille'] ?? '') . (($localisation['pleiadesVille'] ?? null) ? ' (' . $localisation['pleiadesVille'] . ')' : '')) ?? '',
            'site'         => (($localisation['nomSite'] ?? '') . (($localisation['pleiadesSite'] ?? null) ? ' (' . $localisation['pleiadesSite'] . ')' : '')) ?? '',
            'latitude'     => $localisation['latitude'] ?? '',
            'longitude'    => $localisation['longitude'] ?? ''
        ];
    }
}
