<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NombreElt
 *
 * @ORM\Table(name="nombre_elt")
 * @ORM\Entity
 */
class NombreElt
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_nombre", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="nombre_elt_id_nombre_seq", allocationSize=1, initialValue=1)
     */
    private $idNombre;

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

    public function getIdNombre(): ?int
    {
        return $this->idNombre;
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
