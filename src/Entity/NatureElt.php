<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NatureElt
 *
 * @ORM\Table(name="nature_elt")
 * @ORM\Entity
 */
class NatureElt
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_nature", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="nature_elt_id_nature_seq", allocationSize=1, initialValue=1)
     */
    private $idNature;

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

    public function getIdNature(): ?int
    {
        return $this->idNature;
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
