<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Attestation
 *
 * @ORM\Table(name="attestation", indexes={@ORM\Index(name="fki_modif_attest_fkey", columns={"modif_attest"}), @ORM\Index(name="fki_create_attest_fkey", columns={"create_attest"}), @ORM\Index(name="IDX_326EC63FDEEAEB60", columns={"id_etat"}), @ORM\Index(name="IDX_326EC63F934C3795", columns={"id_datation"}), @ORM\Index(name="IDX_326EC63F16F64486", columns={"id_loc"})})
 * @ORM\Entity
 */
class Attestation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="attestation_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="ref_source", type="string", length=255, nullable=true)
     */
    private $refSource;

    /**
     * @var string|null
     *
     * @ORM\Column(name="restit_ss", type="text", nullable=true)
     */
    private $restitSs;

    /**
     * @var string|null
     *
     * @ORM\Column(name="restit_avec", type="text", nullable=true)
     */
    private $restitAvec;

    /**
     * @var string|null
     *
     * @ORM\Column(name="translitt", type="text", nullable=true)
     */
    private $translitt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_attest", type="text", nullable=true)
     */
    private $comAttest;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_ope", type="datetime", nullable=false)
     */
    private $dateOpe;

    /**
     * @var int
     *
     * @ORM\Column(name="version", type="integer", nullable=false)
     */
    private $version;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var int|null
     *
     * @ORM\Column(name="source_id", type="integer", nullable=true)
     */
    private $sourceId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_attest_en", type="text", nullable=true)
     */
    private $comAttestEn;

    /**
     * @var int|null
     *
     * @ORM\Column(name="fiab_attest", type="smallint", nullable=true)
     */
    private $fiabAttest;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="prose", type="boolean", nullable=true, options={"default"="1"})
     */
    private $prose = true;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="poesie", type="boolean", nullable=true)
     */
    private $poesie;

    /**
     * @var \EtatFiche
     *
     * @ORM\ManyToOne(targetEntity="EtatFiche")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_etat", referencedColumnName="id")
     * })
     */
    private $idEtat;

    /**
     * @var \Chercheur
     *
     * @ORM\ManyToOne(targetEntity="Chercheur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="create_attest", referencedColumnName="id")
     * })
     */
    private $createAttest;

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
     * @var \Localisation
     *
     * @ORM\ManyToOne(targetEntity="Localisation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_loc", referencedColumnName="id")
     * })
     */
    private $idLoc;

    /**
     * @var \Chercheur
     *
     * @ORM\ManyToOne(targetEntity="Chercheur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modif_attest", referencedColumnName="id")
     * })
     */
    private $modifAttest;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Pratique", mappedBy="idAttest")
     */
    private $idPratique;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="CatOccasion", mappedBy="id")
     */
    private $idCatOcc;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="AMatx", inversedBy="idAttest")
     * @ORM\JoinTable(name="a_mat_attest",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_attest", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_mat", referencedColumnName="id_matx")
     *   }
     * )
     */
    private $idMat;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Occasion", mappedBy="idAttest")
     */
    private $idOcc;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idPratique = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idCatOcc = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idMat = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idOcc = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRefSource(): ?string
    {
        return $this->refSource;
    }

    public function setRefSource(?string $refSource): self
    {
        $this->refSource = $refSource;

        return $this;
    }

    public function getRestitSs(): ?string
    {
        return $this->restitSs;
    }

    public function setRestitSs(?string $restitSs): self
    {
        $this->restitSs = $restitSs;

        return $this;
    }

    public function getRestitAvec(): ?string
    {
        return $this->restitAvec;
    }

    public function setRestitAvec(?string $restitAvec): self
    {
        $this->restitAvec = $restitAvec;

        return $this;
    }

    public function getTranslitt(): ?string
    {
        return $this->translitt;
    }

    public function setTranslitt(?string $translitt): self
    {
        $this->translitt = $translitt;

        return $this;
    }

    public function getComAttest(): ?string
    {
        return $this->comAttest;
    }

    public function setComAttest(?string $comAttest): self
    {
        $this->comAttest = $comAttest;

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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getSourceId(): ?int
    {
        return $this->sourceId;
    }

    public function setSourceId(?int $sourceId): self
    {
        $this->sourceId = $sourceId;

        return $this;
    }

    public function getComAttestEn(): ?string
    {
        return $this->comAttestEn;
    }

    public function setComAttestEn(?string $comAttestEn): self
    {
        $this->comAttestEn = $comAttestEn;

        return $this;
    }

    public function getFiabAttest(): ?int
    {
        return $this->fiabAttest;
    }

    public function setFiabAttest(?int $fiabAttest): self
    {
        $this->fiabAttest = $fiabAttest;

        return $this;
    }

    public function getProse(): ?bool
    {
        return $this->prose;
    }

    public function setProse(?bool $prose): self
    {
        $this->prose = $prose;

        return $this;
    }

    public function getPoesie(): ?bool
    {
        return $this->poesie;
    }

    public function setPoesie(?bool $poesie): self
    {
        $this->poesie = $poesie;

        return $this;
    }

    public function getIdEtat(): ?EtatFiche
    {
        return $this->idEtat;
    }

    public function setIdEtat(?EtatFiche $idEtat): self
    {
        $this->idEtat = $idEtat;

        return $this;
    }

    public function getCreateAttest(): ?Chercheur
    {
        return $this->createAttest;
    }

    public function setCreateAttest(?Chercheur $createAttest): self
    {
        $this->createAttest = $createAttest;

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

    public function getIdLoc(): ?Localisation
    {
        return $this->idLoc;
    }

    public function setIdLoc(?Localisation $idLoc): self
    {
        $this->idLoc = $idLoc;

        return $this;
    }

    public function getModifAttest(): ?Chercheur
    {
        return $this->modifAttest;
    }

    public function setModifAttest(?Chercheur $modifAttest): self
    {
        $this->modifAttest = $modifAttest;

        return $this;
    }

    /**
     * @return Collection|Pratique[]
     */
    public function getIdPratique(): Collection
    {
        return $this->idPratique;
    }

    public function addIdPratique(Pratique $idPratique): self
    {
        if (!$this->idPratique->contains($idPratique)) {
            $this->idPratique[] = $idPratique;
            $idPratique->addIdAttest($this);
        }

        return $this;
    }

    public function removeIdPratique(Pratique $idPratique): self
    {
        if ($this->idPratique->contains($idPratique)) {
            $this->idPratique->removeElement($idPratique);
            $idPratique->removeIdAttest($this);
        }

        return $this;
    }

    /**
     * @return Collection|CatOccasion[]
     */
    public function getIdCatOcc(): Collection
    {
        return $this->idCatOcc;
    }

    public function addIdCatOcc(CatOccasion $idCatOcc): self
    {
        if (!$this->idCatOcc->contains($idCatOcc)) {
            $this->idCatOcc[] = $idCatOcc;
            $idCatOcc->addId($this);
        }

        return $this;
    }

    public function removeIdCatOcc(CatOccasion $idCatOcc): self
    {
        if ($this->idCatOcc->contains($idCatOcc)) {
            $this->idCatOcc->removeElement($idCatOcc);
            $idCatOcc->removeId($this);
        }

        return $this;
    }

    /**
     * @return Collection|AMatx[]
     */
    public function getIdMat(): Collection
    {
        return $this->idMat;
    }

    public function addIdMat(AMatx $idMat): self
    {
        if (!$this->idMat->contains($idMat)) {
            $this->idMat[] = $idMat;
        }

        return $this;
    }

    public function removeIdMat(AMatx $idMat): self
    {
        if ($this->idMat->contains($idMat)) {
            $this->idMat->removeElement($idMat);
        }

        return $this;
    }

    /**
     * @return Collection|Occasion[]
     */
    public function getIdOcc(): Collection
    {
        return $this->idOcc;
    }

    public function addIdOcc(Occasion $idOcc): self
    {
        if (!$this->idOcc->contains($idOcc)) {
            $this->idOcc[] = $idOcc;
            $idOcc->addIdAttest($this);
        }

        return $this;
    }

    public function removeIdOcc(Occasion $idOcc): self
    {
        if ($this->idOcc->contains($idOcc)) {
            $this->idOcc->removeElement($idOcc);
            $idOcc->removeIdAttest($this);
        }

        return $this;
    }

}
