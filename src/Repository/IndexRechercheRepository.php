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
        $results = $this->createQueryBuilder('i')
                        ->select('i')
                        ->where('i.data LIKE :search')
                        ->setParameter('search', '%'.addcslashes($search, '%_').'%')
                        ->getQuery()
                        ->getResult();

        $response = [];
        foreach($results as $result){
            $resultData = $result->getData();

            if($result->getEntite() == "Source"){
                $r = $this->_prepareSourceResult($resultData, $locale);
                $r['linkType'] = "source";
                $r['linkId'] = $result->getId();
                $r['type'] = "source.name";
                $r['field'] = $this->_cleanFieldName(
                    $this->_array_search($resultData, $search)
                );
                $response[] = $r;
            }
            else if($result->getEntite() == "Attestation"){
                $r = $this->_prepareAttestationResult($resultData, $locale);
                $r['linkType'] = "attestation";
                $r['linkId'] = $result->getId();
                $r['type'] = "attestation.name";
                $r['field'] = $this->_cleanFieldName(
                    $this->_array_search($resultData, $search)
                );
                $response[] = $r;
            }
        }
        return $response;
    }

    private function _prepareSourceResult(array $source, string $locale): array
    {
        $mainEdition = array_reduce(
            $source['sourceBiblios'],
            function($carry, $item){ return ($item['editionPrincipale'] ?? false) ? $item : $carry; }
        );
        $reference = $mainEdition === null ? null
            : $mainEdition['titreAbrege'].' '.$mainEdition['reference'];

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

        $attestationIds = implode(',', $source['attestations']);
        $query = $this->getEntityManager()
                      ->createQuery("SELECT a.extraitAvecRestitution FROM \App\Entity\Attestation a WHERE a.id IN ($attestationIds)");
        $extraits = $query->getResult();
        $extraits = array_column($extraits, 'extraitAvecRestitution');

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

        return [
            "reference" => $reference,
            "localisation" => $localisation,
            "datation" => $datation,
            "text" => [$attestation['extraitAvecRestitution']]
        ];
    }

    private function _array_search(array $data, string $search)
    {
        foreach($data as $key => $value){
            if((is_string($value) && \stripos($value, $search) !== false)
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
            'sourceBiblio'           => 'source.fields.source_biblio',
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
            'elementsBiblio'         => 'element.fields.element_biblio',
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
                $value = trim(html_entity_decode(strip_tags($value)));
            }
            else if(is_array($value)) {
                $value = $this->_cleanData($value);
            }
        }
        return array_filter($entityData);
    }
}
