<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Source
 *
 * @ORM\Table(name="source")
 * @ORM\Entity(repositoryClass="App\Repository\SourceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Source
{
    use Traits\Tracked;
    use Traits\Translatable;
    use Traits\TranslatedComment;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @var \Titre|null
     *
     * @ORM\ManyToOne(targetEntity="Titre", fetch="EAGER")
     * @ORM\JoinColumn(name="titre_principal_id", referencedColumnName="id", nullable=true)
     */
    private $titrePrincipal;

    public function getTitrePrincipal(): ?Titre
    {
        return $this->titrePrincipal;
    }

    public function setTitrePrincipal(?Titre $titrePrincipal): self
    {
        $this->titrePrincipal = $titrePrincipal;
        return $this;
    }

    /**
     * @var int
     *
     * @ORM\Column(name="version", type="integer", nullable=false)
     */
    private $version;

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @var bool|null
     *
     * @ORM\Column(name="citation", type="boolean", nullable=true)
     */
    private $citation = false;

    public function getCitation(): ?bool
    {
        return $this->citation;
    }

    public function setCitation(?bool $citation): self
    {
        $this->citation = $citation;
        return $this;
    }

    /**
     * @var bool|null
     *
     * @ORM\Column(name="iconographie", type="boolean", nullable=true)
     */
    private $iconographie = false;

    public function getIconographie(): ?bool
    {
        return $this->iconographie;
    }

    public function setIconographie(?bool $iconographie): self
    {
        $this->iconographie = $iconographie;
        return $this;
    }

    /**
     * @var string|null
     *
     * @ORM\Column(name="url_texte", type="text", nullable=true)
     */
    private $urlTexte;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url_image", type="text", nullable=true)
     */
    private $urlImage;

    public function getUrlTexte(): ?string
    {
        return $this->urlTexte;
    }

    public function setUrlTexte(?string $urlTexte): self
    {
        $this->urlTexte = $urlTexte;
        return $this;
    }

    public function getUrlImage(): ?string
    {
        return $this->urlImage;
    }

    public function setUrlImage(?string $urlImage): self
    {
        $this->urlImage = $urlImage;
        return $this;
    }

    /**
     * @var bool|null
     *
     * @ORM\Column(name="in_situ", type="boolean", nullable=true)
     */
    private $inSitu;

    public function getInSitu(): ?bool
    {
        return $this->inSitu;
    }

    public function setInSitu(?bool $inSitu): self
    {
        $this->inSitu = $inSitu;
        return $this;
    }

    /**
     * @var bool|null
     *
     * @ORM\Column(name="est_datee", type="boolean", nullable=true)
     */
    private $estDatee;

    public function getEstDatee(): ?bool
    {
        return $this->estDatee;
    }

    public function setEstDatee(?bool $estDatee): self
    {
        $this->estDatee = $estDatee;
        return $this;
    }

    /**
     * @var int|null
     *
     * @ORM\Column(name="fiabilite_localisation", type="smallint", nullable=true)
     */
    private $fiabiliteLocalisation;

    public function getFiabiliteLocalisation(): ?int
    {
        return $this->fiabiliteLocalisation;
    }

    public function setFiabiliteLocalisation(?int $fiabiliteLocalisation): self
    {
        $this->fiabiliteLocalisation = $fiabiliteLocalisation;
        return $this;
    }

    /**
     * @var int|null
     *
     * @ORM\Column(name="fiabilite_datation", type="smallint", nullable=true)
     */
    private $fiabiliteDatation;

    public function getFiabiliteDatation(): ?int
    {
        return $this->fiabiliteDatation;
    }

    public function setFiabiliteDatation(?int $fiabiliteDatation): self
    {
        $this->fiabiliteDatation = $fiabiliteDatation;
        return $this;
    }

    /**
     * @var \TypeSupport|null
     *
     * @ORM\ManyToOne(targetEntity="TypeSupport", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_support_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $typeSupport;

    public function getTypeSupport(): ?TypeSupport
    {
        return $this->typeSupport;
    }

    public function setTypeSupport(?TypeSupport $typeSupport): self
    {
        $this->typeSupport = $typeSupport;
        return $this;
    }

    /**
     * @var \CategorieSupport|null
     *
     * @ORM\ManyToOne(targetEntity="CategorieSupport")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_support_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $categorieSupport;

    public function getCategorieSupport(): ?CategorieSupport
    {
        return $this->categorieSupport;
    }

    public function setCategorieSupport(?CategorieSupport $categorieSupport): self
    {
        $this->categorieSupport = $categorieSupport;
        return $this;
    }

    public function setTypeCategorieSupport($data): self
    {
        $this->setCategorieSupport($data['categorieSupport']);
        $this->setTypeSupport($data['typeSupport']);
        return $this;
    }

    public function getTypeCategorieSupport(): array
    {
        return [
            'typeSupport' => $this->getTypeSupport(),
            'categorieSupport' => $this->getCategorieSupport(),
        ];
    }

    /**
     * @var \Materiau|null
     *
     * @ORM\ManyToOne(targetEntity="Materiau", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="materiau_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $materiau;

    public function getMateriau(): ?Materiau
    {
        return $this->materiau;
    }

    public function setMateriau(?Materiau $materiau): self
    {
        $this->materiau = $materiau;
        return $this;
    }

    /**
     * @var \CategorieMateriau|null
     *
     * @ORM\ManyToOne(targetEntity="CategorieMateriau")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_materiau_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $categorieMateriau;

    public function getCategorieMateriau(): ?CategorieMateriau
    {
        return $this->categorieMateriau;
    }

    public function setCategorieMateriau(?CategorieMateriau $categorieMateriau): self
    {
        $this->categorieMateriau = $categorieMateriau;
        return $this;
    }

    public function setTypeCategorieMateriau($data): self
    {
        $this->setCategorieMateriau($data['categorieMateriau']);
        $this->setMateriau($data['materiau']);
        return $this;
    }

    public function getTypeCategorieMateriau(): array
    {
        return [
            'materiau' => $this->getMateriau(),
            'categorieMateriau' => $this->getCategorieMateriau(),
        ];
    }

    /**
     * @var \TypeSource
     *
     * @ORM\ManyToOne(targetEntity="TypeSource", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_source_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $typeSource;

    public function getTypeSource(): ?TypeSource
    {
        return $this->typeSource;
    }

    public function setTypeSource(?TypeSource $typeSource): self
    {
        $this->typeSource = $typeSource;
        return $this;
    }

    /**
     * @var \CategorieSource|null
     *
     * @ORM\ManyToOne(targetEntity="CategorieSource")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_source_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $categorieSource;

    public function getCategorieSource(): ?CategorieSource
    {
        return $this->categorieSource;
    }

    public function setCategorieSource(?CategorieSource $categorieSource): self
    {
        $this->categorieSource = $categorieSource;
        return $this;
    }

    public function setTypeCategorieSource($data): self
    {
        $this->setCategorieSource($data['categorieSource']);
        $this->setTypeSource($data['typeSource']);
        return $this;
    }

    public function getTypeCategorieSource(): array
    {
        return [
            'typeSource' => $this->getTypeSource(),
            'categorieSource' => $this->getCategorieSource(),
        ];
    }

    /**
     * @var \Datation
     *
     * @ORM\OneToOne(targetEntity="Datation", cascade={"persist"}, fetch="EAGER", orphanRemoval=true)
     * @ORM\JoinColumn(name="datation_id", referencedColumnName="id", nullable=true)
     */
    private $datation;

    public function getDatation(): ?Datation
    {
        return $this->datation;
    }

    public function setDatation(?Datation $datation): self
    {
        $this->datation = $datation;
        return $this;
    }

    /**
     * @var \Localisation|null
     *
     * @ORM\OneToOne(targetEntity="Localisation", cascade={"persist"}, fetch="EAGER", orphanRemoval=true)
     * @ORM\JoinColumn(name="localisation_decouverte_id", referencedColumnName="id", nullable=true)
     */
    private $lieuDecouverte;

    public function getLieuDecouverte(): ?Localisation
    {
        return $this->lieuDecouverte;
    }

    public function setLieuDecouverte(?Localisation $lieuDecouverte): self
    {
        $this->lieuDecouverte = $lieuDecouverte;
        return $this;
    }

    /**
     * @var \Localisation|null
     *
     * @ORM\OneToOne(targetEntity="Localisation", cascade={"persist"}, fetch="EAGER", orphanRemoval=true)
     * @ORM\JoinColumn(name="localisation_origine_id", referencedColumnName="id", nullable=true)
     */
    private $lieuOrigine;

    public function getLieuOrigine(): ?Localisation
    {
        return $this->lieuOrigine;
    }

    public function setLieuOrigine(?Localisation $lieuOrigine): self
    {
        $this->lieuOrigine = $lieuOrigine;
        return $this;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->titresCites = new \Doctrine\Common\Collections\ArrayCollection();
        $this->auteurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->langues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sourceBiblios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Titre", fetch="EAGER")
     * @ORM\JoinTable(name="source_titre_cite",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_source", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_titre", referencedColumnName="id")
     *   }
     * )
     */
    private $titresCites;

    /**
     * @return Collection|Titre[]
     */
    public function getTitresCites(): Collection
    {
        return $this->titresCites;
    }

    public function addTitresCite(Titre $titresCite): self
    {
        if (!$this->titresCites->contains($titresCite)) {
            $this->titresCites[] = $titresCite;
        }
        return $this;
    }

    public function removeTitresCite(Titre $titresCite): self
    {
        if ($this->titresCites->contains($titresCite)) {
            $this->titresCites->removeElement($titresCite);
        }
        return $this;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Auteur")
     * @ORM\JoinTable(name="source_auteur",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_source", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_auteur", referencedColumnName="id")
     *   }
     * )
     */
    private $auteurs;

    /**
     * @return Collection|Auteur[]
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }

    public function addAuteur(Auteur $auteur): self
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs[] = $auteur;
        }
        return $this;
    }

    public function removeAuteur(Auteur $auteur): self
    {
        if ($this->auteurs->contains($auteur)) {
            $this->auteurs->removeElement($auteur);
        }
        return $this;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Langue", fetch="EAGER")
     * @ORM\JoinTable(name="source_langue",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_source", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_langue", referencedColumnName="id")
     *   }
     * )
     */
    private $langues;

    /**
     * @return Collection|Langue[]
     */
    public function getLangues(): Collection
    {
        return $this->langues;
    }

    public function concatLangues($lang): string
    {
        if(empty($this->getLangues())){ return ""; }
        $names = $this->getLangues()->map(function($langue) use ($lang) {
            return $langue->getNom($lang);
        });
        $names = $names->toArray();
        sort($names);
        return implode(', ', $names);
    }

    public function addLangue(Langue $langue): self
    {
        if (!$this->langues->contains($langue)) {
            $this->langues[] = $langue;
        }
        return $this;
    }

    public function removeLangue(Langue $langue): self
    {
        if ($this->langues->contains($langue)) {
            $this->langues->removeElement($langue);
        }
        return $this;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SourceBiblio", mappedBy="source", orphanRemoval=true)
     */
    private $sourceBiblios;

    /**
     * @return Collection|SourceBiblio[]
     */
    public function getSourceBiblios(): ?Collection
    {
        return $this->sourceBiblios;
    }

    public function addSourceBiblio(SourceBiblio $sourceBiblio): self
    {
        if (!$this->sourceBiblios->contains($sourceBiblio)) {
            $this->sourceBiblios[] = $sourceBiblio;
        }
        return $this;
    }

    public function removeSourceBiblio(SourceBiblio $sourceBiblio): self
    {
        if ($this->sourceBiblios->contains($sourceBiblio)) {
            $this->sourceBiblios->removeElement($sourceBiblio);
        }
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onCreate(){
        $now = new \DateTime();
        $this->setDateCreation($now);
        $this->setDateModification($now);
        $this->setVersion(1);
        $this->_updateFiabilite();
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate(){
        $now = new \DateTime();
        $this->setDateModification($now);
        $this->setVersion($this->getVersion() + 1);
        $this->_updateFiabilite();
    }

    private function _updateFiabilite(){
        if(!is_null($datation = $this->getDatation())){
            $this->setFiabiliteDatation(abs($datation->getPostQuem() - $datation->getAnteQuem()));
        }
        // if(!is_null($localisation = $this->getLocalisation())){
        //     // TODO
        //     $this->setFiabiliteLocalisation(0);
        // }
    }
}
