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
class Source extends AbstractEntity
{
    use Traits\DatedWithFiability;
    use Traits\EntityId;
    use Traits\Indexed;
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
     * @ORM\ManyToMany(targetEntity="TypeSource")
     * @ORM\JoinTable(name="source_type_source",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_source", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_type_source", referencedColumnName="id")
     *   }
     * )
     */
    private $typeSources;

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
     * @ORM\ManyToOne(targetEntity="Localisation", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="localisation_decouverte_id", referencedColumnName="id", nullable=true)
     */
    private $lieuDecouverte;

    /**
     * @var \Localisation|null
     *
     * @ORM\ManyToOne(targetEntity="Localisation", cascade={"persist"}, fetch="EAGER")
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
     * @Assert\Expression(
     *      "this.hasUniqueBiblio()",
     *      message="unique_biblio"
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
     * @ORM\ManyToOne(targetEntity="VerrouEntite", inversedBy="sources", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="verrou_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * })
     */
    private $verrou;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
        $this->langues = new ArrayCollection();
        $this->sourceBiblios = new ArrayCollection();
        $this->attestations = new ArrayCollection();
        $this->typeSources = new ArrayCollection();
    }

    /**
     * Clone magic method
     */
    public function __clone()
    {
        if ($this->id) {
            $this->id = null;

            // Reset tracking fields
            $this->dateCreation     = null;
            $this->dateModification = null;
            $this->createur         = null;
            $this->dernierEditeur   = null;
            $this->version          = null;
            $this->verrou           = null;

            // Clone datation and localizations
            if ($this->datation !== null) {
                $this->datation = clone $this->datation;
            }

            // Clone sourceBiblios
            $cloneSourceBiblios = new ArrayCollection();
            foreach ($this->sourceBiblios as $sb) {
                $cloneSb = clone $sb;
                $cloneSb->setSource($this);
                $cloneSourceBiblios->add($cloneSb);
            }
            $this->sourceBiblios = $cloneSourceBiblios;

            // Do not clone Attestations
            $this->attestations = new ArrayCollection();
        }
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

    /**
     * @return Collection|TypeSource[]
     */
    public function getTypeSources(): Collection
    {
        return $this->typeSources;
    }

    public function concatTypeSources($lang): string
    {
        if (empty($this->getTypeSources())) {
            return "";
        }
        $names = $this->getTypeSources()->map(function ($type) use ($lang) {
            return $type->getNom($lang);
        });
        $names = $names->toArray();
        sort($names);
        return implode(', ', $names);
    }

    public function addTypeSource(TypeSource $typeSource): self
    {
        if (!$this->typeSources->contains($typeSource)) {
            $this->typeSources[] = $typeSource;
        }
        return $this;
    }

    public function removeTypeSource(TypeSource $typeSource): self
    {
        if ($this->typeSources->contains($typeSource)) {
            $this->typeSources->removeElement($typeSource);
        }
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
        $this->getTypeSources()->clear();
        foreach ($data['typeSources'] as $type) {
            $this->getTypeSources()->add($type);
        }
        return $this;
    }

    public function getTypeCategorieSource(): array
    {
        return [
            'typeSources' => $this->getTypeSources()->getValues(),
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
        if (empty($this->getLangues())) {
            return "";
        }
        $names = $this->getLangues()->map(function ($langue) use ($lang) {
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
        foreach ($this->getSourceBiblios() as $sb) {
            if ($sb->getEditionPrincipale() && $sb->getBiblio() !== null) {
                return $sb;
            }
        }
        return null;
    }

    public function hasUniqueBiblio(): bool
    {
        $biblios = [];
        foreach ($this->getSourceBiblios() as $sb) {
            if ($sb->getBiblio() != null && $sb->getBiblio()->getId()) {
                $biblios[] = $sb->getBiblio()->getId();
            }
        }
        return count(array_unique($biblios)) == count($biblios);
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
    public function _updateFiabiliteLocalisation()
    {
        $fiabLocalisation = 5;
        $lieu = $this->getInSitu() ? $this->getLieuDecouverte() : $this->getLieuOrigine();
        if (!is_null($lieu)) {
            if (!empty($lieu->getNomSite())) {
                $fiabLocalisation = 1;
            } else if (!empty($lieu->getNomVille())) {
                $fiabLocalisation = 2;
            } else if (!is_null($lieu->getSousRegion())) {
                $fiabLocalisation = 3;
            } else if (!is_null($lieu->getGrandeRegion())) {
                $fiabLocalisation = 4;
            } else {
                $fiabLocalisation = 5;
            }
        }
        $this->setFiabiliteLocalisation($fiabLocalisation);
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function _updateInSituLocalisation()
    {
        if ($this->getInSitu() === true) {
            $this->setLieuOrigine($this->getLieuDecouverte());
        }
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

    public function toArray(): array
    {
        $getTranslatedName = function ($entry) {
            return $entry->getTranslatedName();
        };

        return [
            'categorieSource' => $this->categorieSource->toArray(),
            'typeSource'      => $this->typeSources->map(function ($t) {
                return $t->toArray();
            })->getValues(),
            'langues'         => $this->langues->map(function ($l) {
                return $l->toArray();
            })->getValues(),
            'auteurs'         => $this->auteurs->map(function ($l) {
                return $l->toArray();
            })->getValues(),
            'titrePrincipal'  => $this->titrePrincipal === null ? null : array_merge(
                $this->titrePrincipal->getTranslatedName(),
                ['auteurs' => $this->titrePrincipal->getAuteurs()->map($getTranslatedName)->getValues()]
            ),
            'categorieMateriau' => $this->categorieMateriau === null ? null : $this->categorieMateriau->toArray(),
            'typeMateriau'      => $this->materiau === null ? null : $this->materiau->toArray(),
            'categorieSupport'  => $this->categorieSupport === null ? null : $this->categorieSupport->toArray(),
            'typeSupport'       => $this->typeSupport === null ? null : $this->typeSupport->toArray(),
            'datation'          => $this->datation === null ? null : $this->datation->toArray(),
            'fiabiliteDatation' => $this->datation === null ? null : $this->fiabiliteDatation,
            'lieuDecouverte'    => $this->lieuDecouverte === null ? null : $this->lieuDecouverte->toArray(),
            'lieuOrigine'       => $this->inSitu
                ? ($this->lieuDecouverte === null ? null : $this->lieuDecouverte->toArray())
                : ($this->lieuOrigine === null ? null : $this->lieuOrigine->toArray()),
            'sourceBiblios' => $this->sourceBiblios->map(function ($sb) {
                return array_merge($sb->getBiblio()->toArray(), [
                    'editionPrincipale' => $sb->getEditionPrincipale(),
                    'reference'         => $sb->getReferenceSource()
                ]);
            })->getValues(),
            'attestations'  => $this->attestations->map(function ($att) {
                return $att->getId();
            })->toArray(),
            'commentaireFr' => $this->commentaireFr,
            'commentaireEn' => $this->commentaireEn
        ];
    }
}
