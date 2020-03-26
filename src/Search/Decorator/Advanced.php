<?php

namespace App\Search\Decorator;

use App\Entity\IndexRecherche;

class Advanced {

    public static function decorate(array $data, array $allData, string $locale): array
    {
        $result = [];
        foreach($data as $entity) {

            $entityData = $entity->getData();
            $method = "decorate".$entity->getEntite();
            $result[] = self::$method($entity, $allData, $locale);
        }
        return $result;
    }

    protected static function decorateSource(IndexRecherche $entity, array $allData, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $data = $entity->getData();

        $result = [];

        $mainEdition = array_reduce(
            $data['sourceBiblios'],
            function($carry, $item){ return ($item['editionPrincipale'] ?? false) ? $item : $carry; }
        );
        $result['reference'] = $mainEdition === null ? null
            : implode(' ', array_filter([
                $mainEdition['titreAbrege'] ?? null,
                $mainEdition['reference'] ?? null
            ]));


        foreach(['categorieSource', 'categorieMateriau', 'typeMateriau', 'categorieSupport', 'typeSupport', 'titrePrincipal'] as $manyToOneField){
            $result[$manyToOneField] = array_key_exists($manyToOneField, $data) ? $data[$manyToOneField][$nameField] : '';
        }

        foreach(['typeSource', 'langues', 'auteurs'] as $manyToManyField){
            if(array_key_exists($manyToManyField, $data)){
                $result[$manyToManyField] = array_map(function($item) use ($nameField){
                    return $item[$nameField];
                }, $data[$manyToManyField]);
            }
            else {
                $result[$manyToManyField] = [];
            }
        }

        $result['datation'] = self::_decorateDatation($data['datation'] ?? []);
        $result['lieuOrigine'] = self::_decorateLocalisation($data['lieuOrigine'] ?? [], $locale);
        $result['lieuDecouverte'] = self::_decorateLocalisation($data['lieuDecouverte'] ?? [], $locale);

        $result['extraits'] = array_filter(array_map(
            function($att){ return $att->getData()['extraitAvecRestitution'] ?? $att->getData()['translitteration'] ?? ''; },
            array_filter(
                $allData,
                function($e) use ($data) {
                    return $e->getEntite() == "Attestation" && in_array($e->getId(), $data['attestations'] ?? []);
                }
            )
        ));

        // Add link data
        $result['link'] = ['type' => strtolower($entity->getEntite()), 'id' => $entity->getId()];

        return ['source' => $result];
    }

    protected static function decorateAttestation(IndexRecherche $entity, array $allData, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $data = $entity->getData();

        $result = [
            "passage"                => $data['passage'] ?? '',
            "extraitAvecRestitution" => $data['extraitAvecRestitution'] ?? '',
            "translitteration"       => $data['translitteration'] ?? '',
        ];

        foreach(['traductions', 'pratiques'] as $manyToManyField){
            if(array_key_exists($manyToManyField, $data)){
                $result[$manyToManyField] = array_map(function($item) use ($nameField){
                    return $item[$nameField];
                }, $data[$manyToManyField]);
            }
            else {
                $result[$manyToManyField] = [];
            }
        }

        $result['occasions'] = array_map(function($o) use ($nameField) {
            return ($o['categorieOccasion'][$nameField] ?? '') . ' > ' . ($o['occasion'][$nameField] ?? '');
        }, $data['occasions'] ?? []);

        $result['materiels'] = array_map(function($o) use ($nameField) {
            return ($o['categorieMateriel'][$nameField] ?? '')
                . ' > '
                . ($o['materiel'][$nameField] ?? '')
                . '('
                . ($o['quantite'] ?? '?')
                . ')';
        }, $data['materiels'] ?? []);

        $result['datation'] = self::_decorateDatation($data['datation'] ?? []);
        $result['localisation'] = self::_decorateLocalisation($data['localisation'] ?? [], $locale);

        // Add link data
        $result['link'] = ['type' => strtolower($entity->getEntite()), 'id' => $entity->getId()];

        // Find source and get its data
        $sourceId = $data['source'];
        $source = array_reduce($allData, function($result, $e) use ($sourceId){
            return $result ?? (($e->getEntite() == "Source" && $e->getId() == $sourceId) ? $e : null);
        }, null);
        $sourceData = self::decorateSource($source, $allData, $locale);

        return array_merge($sourceData, ['attestation' => $result]);
    }

    protected static function decorateElement(IndexRecherche $entity, array $allData, string $locale): array
    {
        $nameField = "nom" . ucfirst(strtolower($locale));

        $data = $entity->getData();

        $result = [
            "etatAbsolu" => $data['etatAbsolu'] ?? '',
            "betaCode"   => $data['betaCode'] ?? '',
            "natureElement" => ($data['natureElement'] ?? [])[$nameField] ?? ''
        ];

        foreach(['traductions', 'categories'] as $manyToManyField){
            if(array_key_exists($manyToManyField, $data)){
                $result[$manyToManyField] = array_map(function($item) use ($nameField){
                    return $item[$nameField];
                }, $data[$manyToManyField]);
            }
            else {
                $result[$manyToManyField] = [];
            }
        }

        $result['localisation'] = self::_decorateLocalisation($data['localisation'] ?? [], $locale);
        $result['datation'] = self::_decorateDatation($data['datation'] ?? [], $locale);

        // Add link data
        $result['link'] = ['type' => strtolower($entity->getEntite()), 'id' => $entity->getId()];

        return ['element' => $result];
    }

    protected static function _decorateDatation(array $datation): string
    {
        return implode(
            ' / ',
            array_filter([
                $datation['postQuem'] ?? null,
                $datation['anteQuem'] ?? null
            ])
        );
    }

    protected static function _decorateLocalisation(array $localisation, string $locale): string
    {
        return $localisation['nomSite']
            ?? $localisation['nomVille']
            ?? $localisation['grandeRegion']['nom'.ucFirst($locale)]
            ?? $localisation['sousRegion']['nom'.ucFirst($locale)]
            ?? '';
    }

}