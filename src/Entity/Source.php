<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Source
 *
 * @ORM\Table(name="source", indexes={@ORM\Index(name="fki_id_datation_fkey", columns={"id_datation"}), @ORM\Index(name="fki_create_user_fkey", columns={"create_source"}), @ORM\Index(name="fki_modif_user_fkey", columns={"modif_source"})})
 * @ORM\Entity(repositoryClass="App\Repository\SourceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Source
{
    use Traits\Translatable;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="source_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @var \Titre|null
     *
     * @ORM\OneToOne(targetEntity="Titre", fetch="EAGER", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_titre", referencedColumnName="id_titre", nullable=true)
     * })
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
    private $inSitu = false;

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
     * @var int|null
     *
     * @ORM\Column(name="fiab_loc", type="smallint", nullable=true)
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
     * @var string|null
     *
     * @ORM\Column(name="com_source", type="text", nullable=true)
     */
    private $commentaireSourceFr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_source_en", type="text", nullable=true)
     */
    private $commentaireSourceEn;

    public function getCommentaireSourceFr(): ?string
    {
        return $this->commentaireSourceFr;
    }

    public function setCommentaireSourceFr(?string $commentaireSourceFr): self
    {
        $this->commentaireSourceFr = $commentaireSourceFr;
        return $this;
    }
    public function getCommentaireSourceEn(): ?string
    {
        return $this->commentaireSourceEn;
    }

    public function setCommentaireSourceEn(?string $commentaireSourceEn): self
    {
        $this->commentaireSourceEn = $commentaireSourceEn;
        return $this;
    }

    public function getCommentaireSource(?string $lang): ?string
    {
        if($lang == 'fr'){
            return $this->commentaireSourceFr;
        } else {
            return $this->commentaireSourceEn;
        }
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $dateCreation;

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ope", type="datetime", nullable=false, options={"default": "CURRENT_TIMESTAMP"})
     */
    private $dateModification;

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;
        return $this;
    }

    /**
     * @var \Chercheur
     *
     * @ORM\ManyToOne(targetEntity="Chercheur", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="create_source", referencedColumnName="id", nullable=false)
     * })
     */
    private $createur;

    public function getCreateur(): ?Chercheur
    {
        return $this->createur;
    }

    public function setCreateur(?Chercheur $createur): self
    {
        $this->createur = $createur;
        return $this;
    }

    /**
     * @var \Chercheur|null
     *
     * @ORM\ManyToOne(targetEntity="Chercheur", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modif_source", referencedColumnName="id", nullable=true)
     * })
     */
    private $dernierEditeur;

    public function getDernierEditeur(): ?Chercheur
    {
        return $this->dernierEditeur;
    }

    public function setDernierEditeur(?Chercheur $dernierEditeur): self
    {
        $this->dernierEditeur = $dernierEditeur;
        return $this;
    }

    /**
     * @var \TypeSupport|null
     *
     * @ORM\ManyToOne(targetEntity="TypeSupport", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_support", referencedColumnName="id", nullable=true)
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
     * @var \Materiau|null
     *
     * @ORM\ManyToOne(targetEntity="Materiau", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_mat", referencedColumnName="id_mat", nullable=true)
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
     * @var \TypeSource
     *
     * @ORM\ManyToOne(targetEntity="TypeSource", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_typo", referencedColumnName="id", nullable=false)
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
     * @var \Datation
     *
     * @ORM\ManyToOne(targetEntity="Datation", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_datation", referencedColumnName="id")
     * })
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
     * @ORM\ManyToMany(targetEntity="Titre", inversedBy="sourcesCitees", fetch="EAGER")
     * @ORM\JoinTable(name="titre_cite",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_source", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_titre", referencedColumnName="id_titre")
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
     * @ORM\ManyToMany(targetEntity="Auteur", inversedBy="sources")
     * @ORM\JoinTable(name="ecrit",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_source", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_auteur", referencedColumnName="id_auteur")
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
     * @ORM\JoinTable(name="a_langue",
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
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate(){
        $now = new \DateTime();
        $this->setDateModification($now);
        $this->setVersion($this->getVersion() + 1);
    }
}
