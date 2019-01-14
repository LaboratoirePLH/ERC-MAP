<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SsReg
 *
 * @ORM\Table(name="ss_reg", indexes={@ORM\Index(name="idx_ss_reg_gid_reg", columns={"gid_reg"})})
 * @ORM\Entity
 */
class SsReg
{
    /**
     * @var int
     *
     * @ORM\Column(name="gid_ssreg", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ss_reg_gid_ssreg_seq", allocationSize=1, initialValue=1)
     */
    private $gidSsreg;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var geometry|null
     *
     * @ORM\Column(name="geom", type="geometry", nullable=true, options={"geometry_type"="POINT", "srid"=4326})
     */
    private $geom;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id", type="smallint", nullable=true)
     */
    private $id;

    /**
     * @var \GdeReg
     *
     * @ORM\ManyToOne(targetEntity="GdeReg")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="gid_reg", referencedColumnName="gid_reg")
     * })
     */
    private $gidReg;

    public function getGidSsreg(): ?int
    {
        return $this->gidSsreg;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getGidReg(): ?GdeReg
    {
        return $this->gidReg;
    }

    public function setGidReg(?GdeReg $gidReg): self
    {
        $this->gidReg = $gidReg;

        return $this;
    }


}
