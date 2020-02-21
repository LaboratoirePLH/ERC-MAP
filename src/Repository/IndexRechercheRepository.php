<?php

namespace App\Repository;

use App\Entity\IndexRecherche;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method IndexRecherche|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndexRecherche|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndexRecherche[]    findAll()
 * @method IndexRecherche[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndexRechercheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndexRecherche::class);
    }

    public function fullRebuild()
    {
        return $this->rebuildByEntityType('Source')
             + $this->rebuildByEntityType('Attestation')
             + $this->rebuildByEntityType('Element');
    }

    public function rebuildByEntityType(string $entityType)
    {
        // Ensure $entityType has correct case
        $entityType = \ucfirst(\strtolower($entityType));

        // Remove all of entity type
        $this->createQueryBuilder('clear_index')
             ->delete("App\Entity\IndexRecherche", "i")
             ->where('i.entite = :entityType')
             ->setParameter('entityType', $entityType)
             ->getQuery()
             ->execute();

        // Select all entities of type
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT e FROM \App\Entity\\$entityType e");
        $all = $query->getResult();

        // var_dump($entityType, count($all), $query->getDQL());die;

        // For each entity
        foreach($all as $entity){
            // call toArray function
            $entityData = $entity->toArray();
            // Clean data to optimize it for indexation
            $entityData = $this->_cleanData($entityData);

            // store given data
            $newEntry = new IndexRecherche;
            $newEntry->setEntite($entityType);
            $newEntry->setId($entity->getId());
            $newEntry->setData($entityData);
            $em->persist($newEntry);
        }

        $em->flush();
        return count($all);
    }

    public function simpleSearch(string $search, string $locale): array
    {
        $normalized_search = strtolower(\App\Utils\StringHelper::removeAccents($search));
        $results = $this->createQueryBuilder('i')
                        ->select('i')
                        ->where('unaccent(lower(i.data)) LIKE :search')
                        ->setParameter('search', '%'.addcslashes($normalized_search, '%_').'%')
                        ->getQuery()
                        ->getResult();

        $response = [];
        foreach($results as $entity){
            $prepared = $this->_prepareResult($entity, $locale, $search);
            if($prepared !== false){
                $response[] = $prepared;
            }
        }
        return $response;
    }

    public function guidedSearch(array $criteria, string $locale): array
    {
        // Get all data and sort it by entity type
        $all = $this->findAll();
        $sources = [];
        $attestations = [];
        $elements = [];
        foreach($all as $e){
            $targetArray = strtolower($e->getEntite()) . 's';
            $$targetArray[$e->getId()] = $e->getData();
        }

        $response = [];
        foreach($all as $e){
            $entityData = $e->getData();
            // Filter by names
            if(array_key_exists('names', $criteria)){
                $names = array_map('intval', $criteria['names']);

                if($e->getEntite() === 'Element' && !in_array($entityData['id'], $names)){ continue; }
                else if($e->getEntite() === 'Attestation' && empty(array_intersect(($entityData['elementIds'] ?? []), $names))){ continue; }
                else if($e->getEntite() === 'Source'){
                    $elements = [];
                    foreach($entityData['attestations'] as $aId){
                        if(array_key_exists($aId, $attestations) && array_key_exists('elementsIds', $attestations[$aId])){
                            $elements = array_merge($elements, ($attestations[$aId]['elementIds'] ?? []));
                        }
                    }
                    if(empty(array_intersect($elements, $names))){ continue; }
                }
            }

            // Filter by languages
            if(array_key_exists('languages', $criteria)){
                $languages = array_map('intval', $criteria['languages']);
                $eLanguages = [];
                if($e->getEntite() === 'Element'){
                    // TODO : Add language filtering on elements ?
                    continue;
                }
                else if($e->getEntite() === 'Attestation'){
                    $sourceId = $entityData['source'];
                    if(array_key_exists($sourceId, $sources)){
                        $eLanguages = $sources[$sourceId]['langues'];
                    }
                }
                else if($e->getEntite() === 'Source'){
                    $eLanguages = $entityData['langues'];
                }

                $eLanguages = array_map(function($l){ return $l['id']; }, $eLanguages);

                if(empty(array_intersect($eLanguages, $languages))){ continue; }
            }

            // Filter by datation
            if(!empty(array_intersect(array_keys($criteria['datation'] ?? []), ['post_quem', 'ante_quem']))){
                $post_quem = intval($criteria['datation']['post_quem']) ?? null;
                $ante_quem = intval($criteria['datation']['ante_quem']) ?? null;
                $exact = $criteria['datation']['exact'] ?? false;
                if(!is_null($post_quem) || !is_null($ante_quem)){
                    $datation = null;
                    if($e->getEntite() === 'Element'){
                        // TODO : Add datation filtering on elements ?
                        continue;
                    }
                    else if($e->getEntite() === 'Attestation'){
                        $sourceId = $entityData['source'];
                        $datation = $entityData['datation'] ?? null;
                        if($datation == null && array_key_exists($sourceId, $sources)){
                            $datation = $sources[$sourceId]['datation'] ?? null;
                        }
                    }
                    else if($e->getEntite() === 'Source'){
                        $datation = $entityData['datation'] ?? null;
                    }
                    if($datation === null) { continue; }
                    else if(!!$exact && !is_null($post_quem) && !is_null($ante_quem)){
                        if(is_null($datation['postQuem'] ?? null) || $datation['postQuem'] < $post_quem){ continue; }
                        if(is_null($datation['anteQuem'] ?? null) || $datation['anteQuem'] > $ante_quem){ continue; }
                    }
                    else {
                        if(!(($datation['post_quem'] ?? -3000) <= ($ante_quem ?? 3000)
                            && ($datation['ante_quem'] ?? 3000) >= ($post_quem ?? -3000))){
                                continue;
                            }
                    }
                }
            }

            // Filter by localisation
            if(array_key_exists('locations', $criteria)){
                $locations = array_map(function($l){ return array_map('intval', json_decode($l)); }, $criteria['locations']);
                $eLocations = [];
                if($e->getEntite() === 'Element' || $e->getEntite() === 'Attestation'){
                    $eLocations[] = $entityData['localisation'] ?? null;
                }
                else if($e->getEntite() === 'Source'){
                    $eLocations[] = $entityData['lieuDecouverte'] ?? null;
                    $eLocations[] = $entityData['lieuOrigine'] ?? null;
                }
                $eLocations = array_filter($eLocations);
                $matched = false;
                foreach($eLocations as $el){
                    foreach($locations as $l){
                        switch(count($l)){
                            case 1:
                                if(array_key_exists('grandeRegion', $el) && $l[0] == $el['grandeRegion']['id']){
                                    $matched = true;
                                    break 3; // Break switch + 2x foreach loops
                                }
                                break;
                            case 2:
                                if(array_key_exists('grandeRegion', $el) && $l[0] == $el['grandeRegion']['id']
                                && array_key_exists('sousRegion', $el) && $l[1] == $el['sousRegion']['id']){
                                    $matched = true;
                                    break 3; // Break switch + 2x foreach loops
                                }
                                break;
                            case 3:
                                if($l[2] == $el['pleiadesVille']){
                                    $matched = true;
                                    break 3; // Break switch + 2x foreach loops
                                }
                                break;
                        }
                    }
                }
                if(!$matched){
                    continue;
                }
            }

            // Filter by source categories/types
            if(array_key_exists('sourceTypes', $criteria)){
                $sourceTypes = array_map(function($l){ return array_map('intval', json_decode($l)); }, $criteria['sourceTypes']);
                $sourceData = null;
                if($e->getEntite() === 'Element'){
                    // TODO : Add language filtering on elements ?
                    continue;
                }
                else if($e->getEntite() === 'Attestation'){
                    $sourceId = $entityData['source'];
                    if(array_key_exists($sourceId, $sources)){
                        $sourceData = $sources[$sourceId];
                    }
                }
                else if($e->getEntite() === 'Source'){
                    $sourceData = $entityData;
                }
                if($sourceData == null){ continue; }
                $matched = false;
                foreach($sourceTypes as $st){
                    if(count($st) === 1 && array_key_exists('categorieSource', $sourceData)
                        && $st[0] == $sourceData['categorieSource']['id']){
                        $matched = true;
                        break;
                    }
                    if(count($st) === 2 && array_key_exists('typeSource', $sourceData)
                        && in_array($st[1], array_column($sourceData['typeSource'], 'id'))){
                        $matched = true;
                        break;
                    }
                }
                if(!$matched){
                    continue;
                }
            }

            // Filter by agents
            if(array_key_exists('agents', $criteria)){
                $agents = array_map(function($a){
                    $a = json_decode($a);
                    return [$a[0], intval($a[1])];
                }, $criteria['agents']);

                $eAgents = [];
                if($e->getEntite() === 'Element'){
                    // TODO : Add agents filtering on elements ?
                    continue;
                }
                else if($e->getEntite() === 'Attestation'){
                    $eAgents = array_merge($eAgents, $entityData['agents']);
                }
                else if($e->getEntite() === 'Source'){
                    foreach($entityData['attestations'] as $aId){
                        if(array_key_exists($aId, $attestations)){
                            $eAgents = array_merge($eAgents, $attestations[$aId]['agents']);
                        }
                    }
                }
                if(empty($agents)){ continue; }

                $matched = false;
                foreach($eAgents as $ea){
                    foreach($agents as $a){
                        if(in_array($a[1], array_column($ea[$a[0] . 's'] ?? [], 'id'))){
                            $matched = true;
                            break 2; // break 2x foreach
                        }
                    }
                }
                if(!$matched){
                    continue;
                }
            }

            // If we get here, then we matched all the given filters, so we add the record
            $prepared = $this->_prepareResult($e, $locale);
            if($prepared !== false){
                $response[] = $prepared;
            }
        }
        return $response;
    }

    private function _prepareResult(IndexRecherche $entity, string $locale, $search = null)
    {
        $entityData = $entity->getData();

        if($search !== null){
            $fieldName = $this->_cleanFieldName($this->_array_search($entityData, $search));
            // If field name is empty, it means it is not a real match (for exemple a partial match on a numeric value)
            if(empty($fieldName)){
                return false;
            }
        }

        if($entity->getEntite() == "Source"){
            $r = $this->_prepareSourceResult($entityData, $locale);
            $r['linkType'] = "source";
            $r['linkId'] = $entity->getId();
            $r['type'] = "source.name";
            // TODO : how do we determine the "field" column in non textual searches ?
            $r['field'] = $search === null ? [] : $fieldName;
            return $r;
        }
        else if($entity->getEntite() == "Attestation"){
            $r = $this->_prepareAttestationResult($entityData, $locale);
            $r['linkType'] = "attestation";
            $r['linkId'] = $entity->getId();
            $r['type'] = "attestation.name";
            // TODO : how do we determine the "field" column in non textual searches ?
            $r['field'] = $search === null ? [] : $fieldName;
            return $r;
        }
        else if($entity->getEntite() == "Element"){
            return false;
        }
        return false;
    }

    private function _prepareSourceResult(array $source, string $locale): array
    {
        $mainEdition = array_reduce(
            $source['sourceBiblios'],
            function($carry, $item){ return ($item['editionPrincipale'] ?? false) ? $item : $carry; }
        );
        $reference = $mainEdition === null ? null
            : implode(' ', array_filter([
                $mainEdition['titreAbrege'] ?? null,
                $mainEdition['reference'] ?? null
            ]));

        $localisation = $source['lieuOrigine'] ?? $source['lieuDecouverte'] ?? null;
        if($localisation !== null){
            $localisation = $localisation['nomSite'] ?? $localisation['nomVille'] ?? $localisation['grandeRegion']['nom'.ucFirst($locale)] ?? $localisation['sousRegion']['nom'.ucFirst($locale)] ?? null;
        }

        $datation = array_key_exists('datation', $source) ? (
            implode(
                ' / ',
                array_filter([
                    $source['datation']['postQuem'] ?? null,
                    $source['datation']['anteQuem'] ?? null
                ])
            )
        ) : null;

        $attestationIds = implode(',', $source['attestations'] ?? []);
        $extraits = [];
        if(!empty($attestationIds)){
            $query = $this->getEntityManager()
                          ->createQuery("SELECT a.extraitAvecRestitution FROM \App\Entity\Attestation a WHERE a.id IN ($attestationIds)");
            $result = $query->getResult();
            foreach($result as $row){
                $text = $row['extraitAvecRestitution'] ?? $row['translitteration'] ?? "";
                $extraits[] = \App\Utils\StringHelper::ellipsis($text);
            }
        }

        return [
            "reference" => $reference,
            "localisation" => $localisation,
            "datation" => $datation,
            "text" => $extraits
        ];
    }

    private function _prepareAttestationResult(array $attestation, string $locale): array
    {
        $sourceId = $attestation['source'];
        $query = $this->getEntityManager()
                      ->createQuery("SELECT partial s.{id}, sb, b FROM \App\Entity\Source s LEFT JOIN s.sourceBiblios sb LEFT JOIN sb.biblio b WHERE s.id = $sourceId");
        $query->setHint(\Doctrine\ORM\Query::HINT_FORCE_PARTIAL_LOAD, 1);
        $source = $query->getOneOrNullResult();
        $mainEdition = $source->getSourceBiblios()
                              ->filter(function($sb){ return $sb->getEditionPrincipale(); })
                              ->first();
        $reference = $mainEdition === null ? null
            : $mainEdition->getBiblio()->getTitreAbrege() . ' ' . $mainEdition->getReferenceSource();

        $localisation = $attestation['localisation'] ?? null;
        if($localisation !== null){
            $localisation = $localisation['nomSite'] ?? $localisation['nomVille'] ?? $localisation['grandeRegion']['nom'.ucFirst($locale)] ?? $localisation['sousRegion']['nom'.ucFirst($locale)] ?? null;
        }
        $datation = array_key_exists('datation', $attestation) ? (
            implode(
                ' / ',
                array_filter([
                    $attestation['datation']['postQuem'] ?? null,
                    $attestation['datation']['anteQuem'] ?? null
                ])
            )
        ) : null;

        $text = $attestation['extraitAvecRestitution'] ?? $attestation['translitteration'] ?? "";

        return [
            "reference" => $reference,
            "localisation" => $localisation,
            "datation" => $datation,
            "text" => [\App\Utils\StringHelper::ellipsis($text)]
        ];
    }

    private function _array_search(array $data, string $search)
    {
        foreach($data as $key => $value){
            if((is_string($value) && \stripos($value, strval($search)) !== false)
             || (is_numeric($value) && $value == $search)){
                return [$key];
            }
            else if(is_array($value)){
                $result = $this->_array_search($value, $search);
                if($result !== false){
                    return array_merge([$key], $result);
                }
            }
        }
        return false;
    }

    private function _cleanFieldName($field): array
    {
        if(!is_array($field)){ return []; }

        $mapping = [
            'commentaireFr'          => 'generic.fields.commentaire_fr',
            'commentaireEn'          => 'generic.fields.commentaire_en',
            'nomFr'                  => 'languages.fr',
            'nomEn'                  => 'languages.en',
            'datation'               => 'generic.fields.datation',
            'traductions'            => 'generic.fields.translations',
            'localisation'           => 'generic.fields.localisation',
            'categorieSource'        => 'source.fields.categorie_source',
            'typeSource'             => 'source.fields.types_source',
            'langues'                => 'source.fields.languages',
            'typeMateriau'           => 'source.fields.materiau',
            'categorieMateriau'      => 'source.fields.categorie_materiau',
            'typeSupport'            => 'source.fields.support',
            'categorieSupport'       => 'source.fields.categorie_support',
            'lieuOrigine'            => 'source.fields.lieu_origine',
            'lieuDecouverte'         => 'source.fields.lieu_decouverte',
            'entitePolitique'        => 'localisation.fields.entite_politique',
            'grandeRegion'           => 'localisation.fields.grande_region',
            'sousRegion'             => 'localisation.fields.sous_region',
            'pleiadesVille'          => 'localisation.fields.pleiades_ville',
            'nomVille'               => 'localisation.fields.nom_ville',
            'nomSite'                => 'localisation.fields.nom_site',
            'topographies'           => 'localisation.fields.topographie',
            'fonctions'              => 'localisation.fields.fonction',
            'sourceBiblios'          => 'source.fields.source_biblio',
            'titreAbrege'            => 'biblio.fields.titre_abrege',
            'titreComplet'           => 'biblio.fields.titre_complet',
            'annee'                  => 'biblio.fields.annee',
            'auteur'                 => 'biblio.fields.auteur',
            'passage'                => 'attestation.fields.passage',
            'extraitAvecRestitution' => 'attestation.fields.extrait_avec_restitution',
            'translitteration'       => 'attestation.fields.translitteration',
            'pratiques'              => 'attestation.fields.pratiques',
            'occasion'               => 'attestation.fields.occasion',
            'categorieOccasion'      => 'attestation.fields.categorie_occasion',
            'materiel'               => 'attestation.fields.materiel',
            'categorieMateriel'      => 'attestation.fields.categorie_materiel',
            'quantite'               => 'generic.fields.quantite',
            'agents'                 => 'agent.name',
            'designation'            => 'agent.fields.designation',
            'agentivite'             => 'agent.fields.agentivite',
            'natures'                => 'agent.fields.nature',
            'genres'                 => 'agent.fields.genre',
            'activites'              => 'agent.fields.activite',
            'elements'               => 'element.name',
            'element'                => 'element.name',
            'etatAbsolu'             => 'element.fields.etat_absolu',
            'betaCode'               => 'element.fields.beta_code',
            'natureElement'          => 'element.fields.nature',
            'elementBiblios'         => 'element.fields.element_biblio',
            'enContexte'             => 'element.fields.en_contexte',
            'etatMorphologique'      => 'element.fields.etat_morphologique',
            'genreElement'           => 'element.fields.genre',
        ];

        foreach($field as &$f){
            if(array_key_exists($f, $mapping)){
                $f = $mapping[$f];
            } else if (is_numeric($f)){
                $f = null;
            }
        }

        return array_values(
            array_unique(
                array_filter($field)
            )
        );
    }

    private function _cleanData(array $entityData): ?array
    {
        // array_filter et strip_tags recursif
        foreach($entityData as &$value){
            if(is_string($value)){
                $value = trim(html_entity_decode($value));
            }
            else if(is_array($value)) {
                $value = $this->_cleanData($value);
            }
        }
        return array_filter($entityData);
    }
}
