<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Localisation
 *
 * @ORM\Table(name="localisation", indexes={@ORM\Index(name="idx_localisation_gid_ssreg", columns={"id_ssreg"}), @ORM\Index(name="idx_localisation_gid_reg", columns={"id_reg"}), @ORM\Index(name="IDX_BFD3CE8FD64EF17D", columns={"entite_pol"})})
 * @ORM\Entity
 */
class Localisation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="localisation_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="pleiades_ville", type="integer", nullable=true)
     */
    private $pleiadesVille;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_ville", type="string", length=255, nullable=true)
     */
    private $nomVille;

    /**
     * @var float|null
     *
     * @ORM\Column(name="lat", type="float", precision=10, scale=0, nullable=true)
     */
    private $lat;

    /**
     * @var float|null
     *
     * @ORM\Column(name="long", type="float", precision=10, scale=0, nullable=true)
     */
    private $long;

    /**
     * @var int|null
     *
     * @ORM\Column(name="pleiades_site", type="integer", nullable=true)
     */
    private $pleiadesSite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_site", type="string", length=255, nullable=true)
     */
    private $nomSite;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="reel", type="boolean", nullable=true)
     */
    private $reel;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_loc", type="text", nullable=true)
     */
    private $comLoc;

    /**
     * @var geometry|null
     *
     * @ORM\Column(name="geom", type="geometry", nullable=true, options={"geometry_type"="POINT", "srid"=4326})
     */
    private $geom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_loc_en", type="text", nullable=true)
     */
    private $comLocEn;

    /**
     * @var \EntitePol
     *
     * @ORM\ManyToOne(targetEntity="EntitePol")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entite_pol", referencedColumnName="id")
     * })
     */
    private $entitePol;

    /**
     * @var \GdeReg
     *
     * @ORM\ManyToOne(targetEntity="GdeReg")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_reg", referencedColumnName="gid_reg")
     * })
     */
    private $idReg;

    /**
     * @var \SsReg
     *
     * @ORM\ManyToOne(targetEntity="SsReg")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_ssreg", referencedColumnName="gid_ssreg")
     * })
     */
    private $idSsreg;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="QTopo", mappedBy="idLoc")
     */
    private $idTopo;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="QFonc", mappedBy="idLoc")
     */
    private $idFonc;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idTopo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idFonc = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPleiadesVille(): ?int
    {
        return $this->pleiadesVille;
    }

    public function setPleiadesVille(?int $pleiadesVille): self
    {
        $this->pleiadesVille = $pleiadesVille;

        return $this;
    }

    public function getNomVille(): ?string
    {
        return $this->nomVille;
    }

    public function setNomVille(?string $nomVille): self
    {
        $this->nomVille = $nomVille;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLong(): ?float
    {
        return $this->long;
    }

    public function setLong(?float $long): self
    {
        $this->long = $long;

        return $this;
    }

    public function getPleiadesSite(): ?int
    {
        return $this->pleiadesSite;
    }

    public function setPleiadesSite(?int $pleiadesSite): self
    {
        $this->pleiadesSite = $pleiadesSite;

        return $this;
    }

    public function getNomSite(): ?string
    {
        return $this->nomSite;
    }

    public function setNomSite(?string $nomSite): self
    {
        $this->nomSite = $nomSite;

        return $this;
    }

    public function getReel(): ?bool
    {
        return $this->reel;
    }

    public function setReel(?bool $reel): self
    {
        $this->reel = $reel;

        return $this;
    }

    public function getComLoc(): ?string
    {
        return $this->comLoc;
    }

    public function setComLoc(?string $comLoc): self
    {
        $this->comLoc = $comLoc;

        return $this;
    }

    public function getGeom()
    {
        return $this->geom;
    }

    public function setGeom($geom): self
    {
        $this->geom = $geom;

        return $this;
    }

    public function getComLocEn(): ?string
    {
        return $this->comLocEn;
    }

    public function setComLocEn(?string $comLocEn): self
    {
        $this->comLocEn = $comLocEn;

        return $this;
    }

    public function getEntitePol(): ?EntitePol
    {
        return $this->entitePol;
    }

    public function setEntitePol(?EntitePol $entitePol): self
    {
        $this->entitePol = $entitePol;

        return $this;
    }

    public function getIdReg(): ?GdeReg
    {
        return $this->idReg;
    }

    public function setIdReg(?GdeReg $idReg): self
    {
        $this->idReg = $idReg;

        return $this;
    }

    public function getIdSsreg(): ?SsReg
    {
        return $this->idSsreg;
    }

    public function setIdSsreg(?SsReg $idSsreg): self
    {
        $this->idSsreg = $idSsreg;

        return $this;
    }

    /**
     * @return Collection|QTopo[]
     */
    public function getIdTopo(): Collection
    {
        return $this->idTopo;
    }

    public function addIdTopo(QTopo $idTopo): self
    {
        if (!$this->idTopo->contains($idTopo)) {
            $this->idTopo[] = $idTopo;
            $idTopo->addIdLoc($this);
        }

        return $this;
    }

    public function removeIdTopo(QTopo $idTopo): self
    {
        if ($this->idTopo->contains($idTopo)) {
            $this->idTopo->removeElement($idTopo);
            $idTopo->removeIdLoc($this);
        }

        return $this;
    }

    /**
     * @return Collection|QFonc[]
     */
    public function getIdFonc(): Collection
    {
        return $this->idFonc;
    }

    public function addIdFonc(QFonc $idFonc): self
    {
        if (!$this->idFonc->contains($idFonc)) {
            $this->idFonc[] = $idFonc;
            $idFonc->addIdLoc($this);
        }

        return $this;
    }

    public function removeIdFonc(QFonc $idFonc): self
    {
        if ($this->idFonc->contains($idFonc)) {
            $this->idFonc->removeElement($idFonc);
            $idFonc->removeIdLoc($this);
        }

        return $this;
    }

}
