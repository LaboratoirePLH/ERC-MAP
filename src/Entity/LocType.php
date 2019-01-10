<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LocType
 *
 * @ORM\Table(name="loc_type")
 * @ORM\Entity
 */
class LocType
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_loc_type", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="loc_type_id_loc_type_seq", allocationSize=1, initialValue=1)
     */
    private $idLocType;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    public function getIdLocType(): ?int
    {
        return $this->idLocType;
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
