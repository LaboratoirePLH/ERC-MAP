<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeSupport
 *
 * @ORM\Table(name="type_support", indexes={@ORM\Index(name="IDX_A82584503826693B", columns={"id_cat_supp"})})
 * @ORM\Entity
 */
class TypeSupport
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="type_support_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

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
     * @var \CatSupport
     *
     * @ORM\ManyToOne(targetEntity="CatSupport")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cat_supp", referencedColumnName="id")
     * })
     */
    private $idCatSupp;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIdCatSupp(): ?CatSupport
    {
        return $this->idCatSupp;
    }

    public function setIdCatSupp(?CatSupport $idCatSupp): self
    {
        $this->idCatSupp = $idCatSupp;

        return $this;
    }


}
