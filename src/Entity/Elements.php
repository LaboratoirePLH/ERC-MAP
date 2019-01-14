<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Elements
 *
 * @ORM\Table(name="elements", indexes={@ORM\Index(name="IDX_444A075D16F64486", columns={"id_loc"}), @ORM\Index(name="IDX_444A075D97EF374A", columns={"id_nature"})})
 * @ORM\Entity
 */
class Elements
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="elements_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat_abs", type="string", length=255, nullable=true)
     */
    private $etatAbs;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat_morpho", type="string", length=255, nullable=true)
     */
    private $etatMorpho;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_elt", type="text", nullable=true)
     */
    private $comElt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_elt_en", type="text", nullable=true)
     */
    private $comEltEn;

    /**
     * @var string|null
     *
     * @ORM\Column(name="beta_code", type="string", length=255, nullable=true)
     */
    private $betaCode;

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
     * @var \NatureElt
     *
     * @ORM\ManyToOne(targetEntity="NatureElt")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_nature", referencedColumnName="id_nature")
     * })
     */
    private $idNature;

    // /**
    //  * @var \Doctrine\Common\Collections\Collection
    //  *
    //  * @ORM\ManyToMany(targetEntity="Elements", mappedBy="idElt1")
    //  */
    // private $idElt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="CategorieElt", mappedBy="idElt")
     */
    private $idCatElt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Biblio", mappedBy="idElt")
     */
    private $idBiblio;

    /**
     * Constructor
     */
    public function __construct()
    {
        // $this->idElt = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idCatElt = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idBiblio = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtatAbs(): ?string
    {
        return $this->etatAbs;
    }

    public function setEtatAbs(?string $etatAbs): self
    {
        $this->etatAbs = $etatAbs;

        return $this;
    }

    public function getEtatMorpho(): ?string
    {
        return $this->etatMorpho;
    }

    public function setEtatMorpho(?string $etatMorpho): self
    {
        $this->etatMorpho = $etatMorpho;

        return $this;
    }

    public function getComElt(): ?string
    {
        return $this->comElt;
    }

    public function setComElt(?string $comElt): self
    {
        $this->comElt = $comElt;

        return $this;
    }

    public function getComEltEn(): ?string
    {
        return $this->comEltEn;
    }

    public function setComEltEn(?string $comEltEn): self
    {
        $this->comEltEn = $comEltEn;

        return $this;
    }

    public function getBetaCode(): ?string
    {
        return $this->betaCode;
    }

    public function setBetaCode(?string $betaCode): self
    {
        $this->betaCode = $betaCode;

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

    public function getIdNature(): ?NatureElt
    {
        return $this->idNature;
    }

    public function setIdNature(?NatureElt $idNature): self
    {
        $this->idNature = $idNature;

        return $this;
    }

    /**
     * @return Collection|Elements[]
     */
    public function getIdElt(): Collection
    {
        return $this->idElt;
    }

    public function addIdElt(Elements $idElt): self
    {
        if (!$this->idElt->contains($idElt)) {
            $this->idElt[] = $idElt;
            $idElt->addIdElt1($this);
        }

        return $this;
    }

    public function removeIdElt(Elements $idElt): self
    {
        if ($this->idElt->contains($idElt)) {
            $this->idElt->removeElement($idElt);
            $idElt->removeIdElt1($this);
        }

        return $this;
    }

    /**
     * @return Collection|CategorieElt[]
     */
    public function getIdCatElt(): Collection
    {
        return $this->idCatElt;
    }

    public function addIdCatElt(CategorieElt $idCatElt): self
    {
        if (!$this->idCatElt->contains($idCatElt)) {
            $this->idCatElt[] = $idCatElt;
            $idCatElt->addIdElt($this);
        }

        return $this;
    }

    public function removeIdCatElt(CategorieElt $idCatElt): self
    {
        if ($this->idCatElt->contains($idCatElt)) {
            $this->idCatElt->removeElement($idCatElt);
            $idCatElt->removeIdElt($this);
        }

        return $this;
    }

    /**
     * @return Collection|Biblio[]
     */
    public function getIdBiblio(): Collection
    {
        return $this->idBiblio;
    }

    public function addIdBiblio(Biblio $idBiblio): self
    {
        if (!$this->idBiblio->contains($idBiblio)) {
            $this->idBiblio[] = $idBiblio;
            $idBiblio->addIdElt($this);
        }

        return $this;
    }

    public function removeIdBiblio(Biblio $idBiblio): self
    {
        if ($this->idBiblio->contains($idBiblio)) {
            $this->idBiblio->removeElement($idBiblio);
            $idBiblio->removeIdElt($this);
        }

        return $this;
    }

}
