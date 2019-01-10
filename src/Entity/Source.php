<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Source
 *
 * @ORM\Table(name="source", indexes={@ORM\Index(name="fki_id_datation_fkey", columns={"id_datation"}), @ORM\Index(name="fki_create_user_fkey", columns={"create_source"}), @ORM\Index(name="fki_modif_user_fkey", columns={"modif_source"})})
 * @ORM\Entity
 */
class Source
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="source_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="citation", type="boolean", nullable=true)
     */
    private $citation = false;

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
    private $inSitu = false;

    /**
     * @var int|null
     *
     * @ORM\Column(name="fiab_loc", type="smallint", nullable=true)
     */
    private $fiabLoc;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_source", type="text", nullable=true)
     */
    private $comSource;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ope", type="datetime", nullable=false, options={"default"="now()"})
     */
    private $dateOpe = 'now()';

    /**
     * @var int
     *
     * @ORM\Column(name="version", type="integer", nullable=false)
     */
    private $version;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_mat", type="smallint", nullable=true)
     */
    private $idMat;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_support", type="smallint", nullable=true)
     */
    private $idSupport;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_type_cat", type="smallint", nullable=true)
     */
    private $idTypeCat;

    /**
     * @var int
     *
     * @ORM\Column(name="id_typo", type="smallint", nullable=false)
     */
    private $idTypo;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_titre", type="integer", nullable=true)
     */
    private $titreId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_source_en", type="text", nullable=true)
     */
    private $comSourceEn;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_cat_support", type="integer", nullable=true)
     */
    private $idCatSupport;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_cat_mat", type="integer", nullable=true)
     */
    private $idCatMat;

    /**
     * @var \Chercheur
     *
     * @ORM\ManyToOne(targetEntity="Chercheur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="create_source", referencedColumnName="id")
     * })
     */
    private $createSource;

    /**
     * @var \Datation
     *
     * @ORM\ManyToOne(targetEntity="Datation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_datation", referencedColumnName="id")
     * })
     */
    private $idDatation;

    /**
     * @var \Chercheur
     *
     * @ORM\ManyToOne(targetEntity="Chercheur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modif_source", referencedColumnName="id")
     * })
     */
    private $modifSource;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Auteur", inversedBy="idSource")
     * @ORM\JoinTable(name="ecrit",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_source", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_auteur", referencedColumnName="id_auteur")
     *   }
     * )
     */
    private $idAuteur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Titre", inversedBy="idSource")
     * @ORM\JoinTable(name="titre_cite",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_source", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_titre", referencedColumnName="id_titre")
     *   }
     * )
     */
    private $idTitre;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAuteur = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idTitre = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCitation(): ?bool
    {
        return $this->citation;
    }

    public function setCitation(?bool $citation): self
    {
        $this->citation = $citation;

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

    public function getFiabLoc(): ?int
    {
        return $this->fiabLoc;
    }

    public function setFiabLoc(?int $fiabLoc): self
    {
        $this->fiabLoc = $fiabLoc;

        return $this;
    }

    public function getComSource(): ?string
    {
        return $this->comSource;
    }

    public function setComSource(?string $comSource): self
    {
        $this->comSource = $comSource;

        return $this;
    }

    public function getDateOpe(): ?\DateTimeInterface
    {
        return $this->dateOpe;
    }

    public function setDateOpe(\DateTimeInterface $dateOpe): self
    {
        $this->dateOpe = $dateOpe;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getIdMat(): ?int
    {
        return $this->idMat;
    }

    public function setIdMat(?int $idMat): self
    {
        $this->idMat = $idMat;

        return $this;
    }

    public function getIdSupport(): ?int
    {
        return $this->idSupport;
    }

    public function setIdSupport(?int $idSupport): self
    {
        $this->idSupport = $idSupport;

        return $this;
    }

    public function getIdTypeCat(): ?int
    {
        return $this->idTypeCat;
    }

    public function setIdTypeCat(?int $idTypeCat): self
    {
        $this->idTypeCat = $idTypeCat;

        return $this;
    }

    public function getIdTypo(): ?int
    {
        return $this->idTypo;
    }

    public function setIdTypo(int $idTypo): self
    {
        $this->idTypo = $idTypo;

        return $this;
    }

    public function getTitreId(): ?int
    {
        return $this->titreId;
    }

    public function setTitreId(?int $titreId): self
    {
        $this->titreId = $titreId;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getComSourceEn(): ?string
    {
        return $this->comSourceEn;
    }

    public function setComSourceEn(?string $comSourceEn): self
    {
        $this->comSourceEn = $comSourceEn;

        return $this;
    }

    public function getIdCatSupport(): ?int
    {
        return $this->idCatSupport;
    }

    public function setIdCatSupport(?int $idCatSupport): self
    {
        $this->idCatSupport = $idCatSupport;

        return $this;
    }

    public function getIdCatMat(): ?int
    {
        return $this->idCatMat;
    }

    public function setIdCatMat(?int $idCatMat): self
    {
        $this->idCatMat = $idCatMat;

        return $this;
    }

    public function getCreateSource(): ?Chercheur
    {
        return $this->createSource;
    }

    public function setCreateSource(?Chercheur $createSource): self
    {
        $this->createSource = $createSource;

        return $this;
    }

    public function getIdDatation(): ?Datation
    {
        return $this->idDatation;
    }

    public function setIdDatation(?Datation $idDatation): self
    {
        $this->idDatation = $idDatation;

        return $this;
    }

    public function getModifSource(): ?Chercheur
    {
        return $this->modifSource;
    }

    public function setModifSource(?Chercheur $modifSource): self
    {
        $this->modifSource = $modifSource;

        return $this;
    }

    /**
     * @return Collection|Auteur[]
     */
    public function getIdAuteur(): Collection
    {
        return $this->idAuteur;
    }

    public function addIdAuteur(Auteur $idAuteur): self
    {
        if (!$this->idAuteur->contains($idAuteur)) {
            $this->idAuteur[] = $idAuteur;
        }

        return $this;
    }

    public function removeIdAuteur(Auteur $idAuteur): self
    {
        if ($this->idAuteur->contains($idAuteur)) {
            $this->idAuteur->removeElement($idAuteur);
        }

        return $this;
    }

    /**
     * @return Collection|Titre[]
     */
    public function getIdTitre(): Collection
    {
        return $this->idTitre;
    }

    public function addIdTitre(Titre $idTitre): self
    {
        if (!$this->idTitre->contains($idTitre)) {
            $this->idTitre[] = $idTitre;
        }

        return $this;
    }

    public function removeIdTitre(Titre $idTitre): self
    {
        if ($this->idTitre->contains($idTitre)) {
            $this->idTitre->removeElement($idTitre);
        }

        return $this;
    }

}
