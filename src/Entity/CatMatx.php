<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CatMatx
 *
 * @ORM\Table(name="cat_matx")
 * @ORM\Entity
 */
class CatMatx
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cat_matx", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="cat_matx_id_cat_matx_seq", allocationSize=1, initialValue=1)
     */
    private $idCatMatx;

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

    public function getIdCatMatx(): ?int
    {
        return $this->idCatMatx;
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


}
