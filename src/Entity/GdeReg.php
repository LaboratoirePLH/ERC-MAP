<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GdeReg
 *
 * @ORM\Table(name="gde_reg")
 * @ORM\Entity
 */
class GdeReg
{
    /**
     * @var int
     *
     * @ORM\Column(name="gid_reg", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="gde_reg_gid_reg_seq", allocationSize=1, initialValue=1)
     */
    private $gidReg;

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
     * @ORM\Column(name="geom", type="geometry", nullable=true)
     */
    private $geom;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id", type="smallint", nullable=true)
     */
    private $id;

    public function getGidReg(): ?int
    {
        return $this->gidReg;
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


}
