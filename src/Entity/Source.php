<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Source
 *
 * @ORM\Table(name="source")
 * @ORM\Entity(repositoryClass="App\Repository\SourceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Source
{
    use Traits\DatedWithFiability;
    use Traits\EntityId;
    use Traits\Tracked;
    use Traits\Translatable;
    use Traits\TranslatedComment;

    /**
     * @var \Titre|null
     *
     * @ORM\ManyToOne(targetEntity="Titre", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumn(name="titre_principal_id", referencedColumnName="id", nullable=true)
     */
    private $titrePrincipal;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="iconographie", type="boolean", nullable=true)
     */
    private $iconographie = false;

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

    /**
     * @var bool|null
     *
     * @ORM\Column(name="in_situ", type="boolean", nullable=true)
     */
    private $inSitu;

    /**
     * @var int|null
     *
     * @ORM\Column(name="fiabilite_localisation", type="smallint", nullable=true)
     */
    private $fiabiliteLocalisation;

    /**
     * @var \TypeSupport|null
     *
     * @ORM\ManyToOne(targetEntity="TypeSupport", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_support_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $typeSupport;

    /**
     * @var \CategorieSupport|null
     *
     * @ORM\ManyToOne(targetEntity="CategorieSupport")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_support_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $categorieSupport;

    /**
     * @var \Materiau|null
     *
     * @ORM\ManyToOne(targetEntity="Materiau", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="materiau_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $materiau;

    /**
     * @var \CategorieMateriau|null
     *
     * @ORM\ManyToOne(targetEntity="CategorieMateriau")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_materiau_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $categorieMateriau;

    /**
     * @var \TypeSource
     *
     * @ORM\ManyToOne(targetEntity="TypeSource", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_source_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $typeSource;

    /**
     * @var \CategorieSource|null
     *
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="CategorieSource")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_source_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $categorieSource;

    /**
     * @var \Localisation|null
     *
     * @ORM\OneToOne(targetEntity="Localisation", cascade={"persist"}, fetch="EAGER", orphanRemoval=true)
     * @ORM\JoinColumn(name="localisation_decouverte_id", referencedColumnName="id", nullable=true)
     */
    private $lieuDecouverte;

    /**
     * @var \Localisation|null
     *
     * @ORM\OneToOne(targetEntity="Localisation", cascade={"persist"}, fetch="EAGER", orphanRemoval=true)
     * @ORM\JoinColumn(name="localisation_origine_id", referencedColumnName="id", nullable=true)
     */
    private $lieuOrigine;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="SourceBiblio", mappedBy="source", orphanRemoval=true)
     * @ORM\OrderBy({"editionPrincipale" = "DESC"})
     * @Assert\Expression(
     *      "this.hasEditionPrincipaleBiblio()",
     *      message="edition_principale"
     * )
     */
    private $sourceBiblios;

    /**
     * @var SourceBiblio|null
     */
    private $editionPrincipaleBiblio;

    /**
     * @var \Projet|null
     *
     * @ORM\ManyToOne(targetEntity="Projet")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_projet", referencedColumnName="id", nullable=true)
     * })
     */
    private $projet;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Attestation", mappedBy="source", orphanRemoval=true)
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $attestations;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\VerrouEntite", inversedBy="sources", fetch="EAGER")
     */
    private $verrou;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->auteurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->langues = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sourceBiblios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->attestations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getTitrePrincipal(): ?Titre
    {
        return $this->titrePrincipal;
    }

    public function setTitrePrincipal(?Titre $titrePrincipal): self
    {
        $this->titrePrincipal = $titrePrincipal;
        return $this;
    }

    public function getIconographie(): ?bool
    {
        return $this->iconographie;
    }

    public function setIconographie(?bool $iconographie): self
    {
        $this->iconographie = $iconographie;
        return $this;
    }

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

    public function getInSitu(): ?bool
    {
        return $this->inSitu;
    }

    public function setInSitu(?bool $inSitu): self
    {
        $this->inSitu = $inSitu;
        return $this;
    }

    public function getFiabiliteLocalisation(): ?int
    {
        return $this->fiabiliteLocalisation;
    }

    public function setFiabiliteLocalisation(?int $fiabiliteLocalisation): self
    {
        $this->fiabiliteLocalisation = $fiabiliteLocalisation;
        return $this;
    }

    public function getTypeSupport(): ?TypeSupport
    {
        return $this->typeSupport;
    }

    public function setTypeSupport(?TypeSupport $typeSupport): self
    {
        $this->typeSupport = $typeSupport;
        return $this;
    }

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

    public function getMateriau(): ?Materiau
    {
        return $this->materiau;
    }

    public function setMateriau(?Materiau $materiau): self
    {
        $this->materiau = $materiau;
        return $this;
    }

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

    public function getTypeSource(): ?TypeSource
    {
        return $this->typeSource;
    }

    public function setTypeSource(?TypeSource $typeSource): self
    {
        $this->typeSource = $typeSource;
        return $this;
    }

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

    public function getLieuDecouverte(): ?Localisation
    {
        return $this->lieuDecouverte;
    }

    public function setLieuDecouverte(?Localisation $lieuDecouverte): self
    {
        $this->lieuDecouverte = $lieuDecouverte;
        return $this;
    }

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

    public function hasEditionPrincipaleBiblio(): bool
    {
        return !is_null($this->getEditionPrincipaleBiblio());
    }

    public function getEditionPrincipaleBiblio(): ?SourceBiblio
    {
        foreach($this->getSourceBiblios() as $sb)
        {
            if($sb->getEditionPrincipale()){
                return $sb;
            }
        }
        return null;
    }

    public function getProjet(): ?Projet
    {
        return $this->projet;
    }

    public function setProjet(?Projet $projet): self
    {
        $this->projet = $projet;
        return $this;
    }

    /**
     * @return Collection|Attestation[]
     */
    public function getAttestations(): ?Collection
    {
        return $this->attestations;
    }

    public function addAttestation(Attestation $attestation): self
    {
        if (!$this->attestations->contains($attestation)) {
            $this->attestations[] = $attestation;
        }
        return $this;
    }

    public function removeAttestation(Attestation $attestation): self
    {
        if ($this->attestations->contains($attestation)) {
            $this->attestations->removeElement($attestation);
        }
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function _updateFiabiliteLocalisation(){
        $fiabLocalisation = 5;
        $lieu = $this->getInSitu() ? $this->getLieuDecouverte() : $this->getLieuOrigine();
        if(!is_null($lieu)){
            if(!empty($lieu->getNomSite())){ $fiabLocalisation = 1; }
            else if(!empty($lieu->getNomVille())){ $fiabLocalisation = 2; }
            else if(!is_null($lieu->getSousRegion())){ $fiabLocalisation = 3; }
            else if(!is_null($lieu->getGrandeRegion())){ $fiabLocalisation = 4; }
            else { $fiabLocalisation = 5; }
        }
        $this->setFiabiliteLocalisation($fiabLocalisation);
    }

    public function getVerrou(): ?VerrouEntite
    {
        return $this->verrou;
    }

    public function setVerrou(?VerrouEntite $verrou): self
    {
        $this->verrou = $verrou;

        return $this;
    }
}
