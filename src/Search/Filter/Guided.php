<?php

namespace App\Search\Filter;

use App\Entity\IndexRecherche;

class Guided
{

    const DEFAULT_POST_QUEM = -3000;
    const DEFAULT_ANTE_QUEM = 3000;

    protected $data;
    protected $sortedData;

    public function __construct(array $data)
    {
        $this->data       = $data;
        $this->sortedData = [
            'sources'      => [],
            'attestations' => [],
            'elements'     => [],
        ];
        $sources = [];
        $attestations = [];
        $elements = [];
        foreach($data as $e){
            $targetArray = strtolower($e->getEntite()) . 's';
            $this->sortedData[$targetArray][$e->getId()] = $e->getData();
        }
    }

    public function filter(array $criteria): array
    {
        $filtered = [];
        foreach($this->data as $e){
            if(array_key_exists('names', $criteria) && !empty($criteria['names'])){
                if(!$this->filterByNames($e, $criteria['names'], ($criteria['names_mode'] ?? null) === 'all')){ continue; }
            }
            if(array_key_exists('languages', $criteria) && !empty($criteria['languages'])){
                if(!$this->filterByLanguages($e, $criteria['languages'], ($criteria['languages_mode'] ?? null) === 'all')){ continue; }
            }
            if(array_key_exists('datation', $criteria) && (
                   (array_key_exists('post_quem', $criteria['datation']) && is_numeric($criteria['datation']['post_quem']))
                || (array_key_exists('ante_quem', $criteria['datation']) && is_numeric($criteria['datation']['ante_quem']))
            ))
            {
                // We need at least one numeric value (empty field will set empty string) to filter by datation
                if(!$this->filterByDatation($e, $criteria['datation'])){ continue; }
            }
            if(array_key_exists('locations', $criteria) && !empty($criteria['locations'])){
                if(!$this->filterByLocations($e, $criteria['locations'])){ continue; }
            }
            if(array_key_exists('sourceTypes', $criteria) && !empty($criteria['sourceTypes'])){
                if(!$this->filterBySourceTypes($e, $criteria['sourceTypes'])){ continue; }
            }
            if(array_key_exists('agents', $criteria) && !empty($criteria['agents'])){
                if(!$this->filterByAgents($e, $criteria['agents'])){ continue; }
            }

            $filtered[] = $e;
        }
        return $filtered;
    }

    protected function filterByNames(IndexRecherche $e, array $criteriaValues, bool $requireAll = false): bool
    {
        $names = array_map('intval', $criteriaValues);
        $eData = $e->getData();

        $entityElements = [];

        if($e->getEntite() === 'Element')
        {
            // If requireAll is true, element is only acceptable if the criteria has a single value equal to the element ID
            // If requireAll is false, element is only acceptable if its ID is in the criteria
            return $requireAll
                ? (count($names) == 1 && $names[0] === intval($eData['id']))
                : in_array($eData['id'], $names);
        }
        else if($e->getEntite() === 'Attestation')
        {
            $entityElements = $eData['elementIds'] ?? [];
        }
        else if($e->getEntite() === 'Source')
        {
            // We fetch all the element IDs from all the attestations of the source
            foreach(($eData['attestations'] ?? []) as $aId){
                if(array_key_exists($aId, $this->sortedData['attestations'])
                    && array_key_exists('elementIds', $this->sortedData['attestations'][$aId]))
                {
                    $entityElements = array_merge($entityElements, ($this->sortedData['attestations'][$aId]['elementIds'] ?? []));
                }
            }
        }

        if(empty($entityElements))
        {
            return false;
        }

        // If requireAll is true, we require all the values in the criteria to be present (intersection count equals to criteria values count)
        // If requireAll is false, we require at least one of the value in the criteria to be present
        return count(array_intersect($names, $entityElements)) >= ($requireAll ? count($names) : 1);
    }

    protected function filterByLanguages(IndexRecherche $e, array $criteriaValues, bool $requireAll = false): bool
    {
        $languages = array_map('intval', $criteriaValues);
        $eData = $e->getData();

        $entityLanguages = [];

        if($e->getEntite() === 'Element')
        {
            // TODO : Add language filtering on elements ?
            return false;
        }
        else if($e->getEntite() === 'Attestation')
        {
            // Get the languages of the source
            $sourceId = $eData['source'];
            if(array_key_exists($sourceId, $this->sortedData['sources']))
            {
                $entityLanguages = $this->sortedData['sources'][$sourceId]['langues'] ?? [];
            }
        }
        else if($e->getEntite() === 'Source')
        {
            $entityLanguages = $eData['langues'] ?? [];
        }

        if(empty($entityLanguages))
        {
            return false;
        }

        // Collect only the ID and convert them to integer
        $entityLanguages = array_map("intval", array_column($entityLanguages, 'id'));

        // If requireAll is true, we require all the values in the criteria to be present (intersection count equals to criteria values count)
        // If requireAll is false, we require at least one of the value in the criteria to be present
        return count(array_intersect($languages, $entityLanguages)) >= ($requireAll ? count($languages) : 1);
    }

    protected function filterByDatation(IndexRecherche $e, array $criteriaValues): bool
    {
        $criteriaPostQuem = intval($criteriaValues['post_quem']) ?? null;
        $criteriaAnteQuem = intval($criteriaValues['ante_quem']) ?? null;
        $criteriaExact    = $criteriaValues['exact'] ?? false;

        $eData = $e->getData();

        $entityDatation = null;

        if($e->getEntite() === 'Element')
        {
            // TODO : Add datation filtering on elements ?
            return false;
        }
        else if($e->getEntite() === 'Attestation'){
            $entityDatation = $eData['datation'] ?? null;

            // Consider that a datation missing both postQuem and anteQuem is non-existent
            if(is_null($entityDatation['postQuem'] ?? null) && is_null($entityDatation['anteQuem'] ?? null)){
                $entityDatation = null;
            }

            // If attestation has no datation, we get it from the source
            $sourceId = $eData['source'];
            if($entityDatation === null && array_key_exists($sourceId, $this->sortedData['sources'])){
                $entityDatation = $this->sortedData['sources'][$sourceId]['datation'] ?? null;
            }
        }
        else if($e->getEntite() === 'Source'){
            $entityDatation = $eData['datation'] ?? null;
        }

        if($entityDatation === null) {
            return false;
        }

        // If exact is checked, we need to have both PostQuem and AnteQuem criteria set
        // Otherwise we consider exact is not checked
        if(!!$criteriaExact && !is_null($criteriaPostQuem) && !is_null($criteriaAnteQuem))
        {
            // If exact is checked, we dismiss entity datations with a missing PostQuem/AnteQuem value
            if(is_null($entityDatation['postQuem'] ?? null) || is_null($entityDatation['anteQuem'] ?? null))
            {
                return false;
            }
            return $criteriaPostQuem <= $entityDatation['postQuem'] && $entityDatation['anteQuem'] <= $criteriaAnteQuem;
        }
        else
        {
            if(is_null($entityDatation['postQuem'] ?? null) && is_null($entityDatation['anteQuem'] ?? null)){
                return false;
            }
            // To accept the record (in non-exact filtering), 2 conditions need to be met :
            //  - datation PostQuem <= criteria AnteQuem
            //  - datation AnteQuem >= criteria PostQuem
            // Any missing value in those 4 will be replaced by default values :
            //  - Default Post Quem = -3000
            //  - Default Ante Quem = 3000
            return ($entityDatation['postQuem'] ?? self::DEFAULT_POST_QUEM) <= ($criteriaAnteQuem ?? self::DEFAULT_ANTE_QUEM)
                && ($entityDatation['anteQuem'] ?? self::DEFAULT_ANTE_QUEM) >= ($criteriaPostQuem ?? self::DEFAULT_POST_QUEM);
        }
    }

    protected function filterByLocations(IndexRecherche $e, array $criteriaValues): bool
    {
        // Each criteria value is a JSON-encoded array of integers, so we unpack and convert everything
        $locations = array_map(function($l){
            return array_map('intval', json_decode($l));
        }, $criteriaValues);

        $eData = $e->getData();

        $entityLocations = [];

        if($e->getEntite() === 'Element')
        {
            $entityLocations[] = $eData['localisation'] ?? null;
        }
        else if($e->getEntite() === 'Attestation')
        {
            $entityLocations[] = $eData['localisation'] ?? null;

            // Also get the localisations from the source
            $sourceId = $eData['source'];
            if(array_key_exists($sourceId, $this->sortedData['sources'])){
                $entityLocations[] = $this->sortedData['sources'][$sourceId]['lieuDecouverte'] ?? null;
                $entityLocations[] = $this->sortedData['sources'][$sourceId]['lieuOrigine'] ?? null;
            }
        }
        else if($e->getEntite() === 'Source'){
            $entityLocations[] = $eData['lieuDecouverte'] ?? null;
            $entityLocations[] = $eData['lieuOrigine'] ?? null;
        }

        // We remove null values
        $entityLocations = array_filter($entityLocations);

        if(empty($entityLocations))
        {
            return false;
        }

        // We return true at the first location matched
        foreach($entityLocations as $el){
            foreach($locations as $l){
                switch(count($l))
                {
                    case 1: // Single ID is greater region
                        if(array_key_exists('grandeRegion', $el) && $l[0] === ($el['grandeRegion']['id'] ?? null))
                        {
                            return true;
                        }
                        break;
                    case 2: // Double ID is greater region + sub region
                        if((array_key_exists('grandeRegion', $el) && $l[0] == ($el['grandeRegion']['id'] ?? null))
                            && (array_key_exists('sousRegion', $el) && $l[1] == ($el['sousRegion']['id'] ?? null)))
                        {
                            return true;
                        }
                        break;
                    case 3: // Triple ID is greater region + sub region + pleiades ID
                        if($l[2] == ($el['pleiadesVille'] ?? 0)){
                            return true;
                        }
                        break;
                }
            }
        }

        // If we get here, no match was found
        return false;
    }

    protected function filterBySourceTypes(IndexRecherche $e, array $criteriaValues): bool
    {
        // Each criteria value is a JSON-encoded array of integers, so we unpack and convert everything
        $sourceTypes = array_map(function($st){
            return array_map('intval', json_decode($st));
        }, $criteriaValues);

        $eData = $e->getData();

        $sourceData = null;

        if($e->getEntite() === 'Element')
        {
            // TODO : Add sourceTypes filtering on elements ?
            return false;
        }
        else if($e->getEntite() === 'Attestation')
        {
            $sourceId = $eData['source'];
            if(array_key_exists($sourceId, $this->sortedData['sources'])){
                $sourceData = $this->sortedData['sources'][$sourceId];
            }
        }
        else if($e->getEntite() === 'Source'){
            $sourceData = $eData;
        }

        // If we have found no source data, we reject the record
        if($sourceData == null){
            return false;
        }

        foreach($sourceTypes as $st){
            // Single ID is source category
            if(array_key_exists('categorieSource', $sourceData) && $st[0] === ($sourceData['categorieSource']['id'] ?? null))
            {
                if(count($st) === 2)
                {
                    // Double ID is source category + source type
                    if(array_key_exists('typeSource', $sourceData) && in_array($st[1], array_column($sourceData['typeSource'], 'id')))
                    {
                        return true;
                    }
                }
                else
                {
                    return true;
                }
            }
        }

        // If we get here, no match was found
        return false;
    }

    protected function filterByAgents(IndexRecherche $e, array $criteriaValues): bool
    {
        // Each criteria value is a JSON-encoded array of a string and an integer, so we unpack and convert everything
        $agents = array_map(function($a){
            $a = json_decode($a);
            return [$a[0], intval($a[1])];
        }, $criteriaValues);

        $eData = $e->getData();

        $entityAgents = [];

        if($e->getEntite() === 'Element')
        {
            // TODO : Add agents filtering on elements ?
            return false;
        }
        else if($e->getEntite() === 'Attestation')
        {
            $entityAgents = $eData['agents'] ?? [];
        }
        else if($e->getEntite() === 'Source')
        {
            // We fetch all the agents from all the attestations of the source
            foreach(($eData['attestations'] ?? []) as $aId)
            {
                if(array_key_exists($aId, $this->sortedData['attestations'])
                    && array_key_exists('agents', $this->sortedData['attestations'][$aId]))
                {
                    $entityAgents = array_merge($entityAgents, $this->sortedData['attestations'][$aId]['agents']);
                }
            }
        }

        if(empty($agents)){
            return false;
        }

        $matched = false;
        foreach($entityAgents as $ea)
        {
            foreach($agents as $a)
            {
                // $a[0] equals 'activite' or 'agentivite'
                // $a[1] is the ID of the activite/agentivite
                // So we get all the IDs of the activites/agentivites subarray,
                // We convert them to integers and check for a match
                $ids = array_map('intval', array_column($ea[$a[0] . 's'] ?? [], 'id'));
                if(in_array($a[1], $ids)){
                    return true;
                }
            }
        }

        // If we get here, no match was found
        return false;
    }
}
