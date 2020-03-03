<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use App\Entity\IndexRecherche;
use App\Search\Filter\Guided as GuidedSearchFilter;
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

    public function rebuildEntry(string $entityType, int $entityId)
    {
        // Ensure $entityType has correct case
        $entityType = \ucfirst(\strtolower($entityType));

        // Removes existing data
        $this->deleteEntry($entityType, $entityId);

        // Fetch up-to-date data
        $query = $this->getEntityManager()->createQuery("SELECT e FROM \App\Entity\\$entityType e WHERE e.id = $entityId");
        $entity = $query->getOneOrNullResult();

        // If entity is found
        if($entity !== null){
            // call toArray function
           $entityData = $entity->toArray();
           // Clean data to optimize it for indexation
           $entityData = $this->_cleanData($entityData);

           // store given data
           $newEntry = new IndexRecherche;
           $newEntry->setEntite($entityType);
           $newEntry->setId($entity->getId());
           $newEntry->setData($entityData);
           $this->getEntityManager()->persist($newEntry);
           $this->getEntityManager()->flush();
        }
    }

    public function deleteEntry(string $entityType, int $entityId)
    {
        // Ensure $entityType has correct case
        $entityType = \ucfirst(\strtolower($entityType));

        // Remove all of entity type
        $this->createQueryBuilder('clear_index')
             ->delete("App\Entity\IndexRecherche", "i")
             ->where('i.entite = :entityType')
             ->andWhere('i.id = :entityId')
             ->setParameter('entityType', $entityType)
             ->setParameter('entityId', $entityId)
             ->getQuery()
             ->execute();
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
        $allData = $this->findAll();

        $filter = new GuidedSearchFilter($allData);
        $filteredData = $filter->filter($criteria);

        $response = [];
        foreach($filteredData as $e){
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
                ], function($v){ return !is_null($v); })
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
            'langues'                => 'source.fields.langues',
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
        return array_filter($entityData, function($value) {
            return is_array($value) ? count($value) : !is_null($value);
        });
    }
}
