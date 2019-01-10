<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AMat
 *
 * @ORM\Table(name="a_mat", indexes={@ORM\Index(name="IDX_9A9F01A47FE4B2B", columns={"id_type"})})
 * @ORM\Entity
 */
class AMat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_mat", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="a_mat_id_mat_seq", allocationSize=1, initialValue=1)
     */
    private $idMat;

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
     * @var \CatMat
     *
     * @ORM\ManyToOne(targetEntity="CatMat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type", referencedColumnName="id")
     * })
     */
    private $idType;

    public function getIdMat(): ?int
    {
        return $this->idMat;
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

    public function getIdType(): ?CatMat
    {
        return $this->idType;
    }

    public function setIdType(?CatMat $idType): self
    {
        $this->idType = $idType;

        return $this;
    }


}
