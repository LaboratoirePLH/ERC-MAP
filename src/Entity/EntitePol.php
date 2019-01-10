<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntitePol
 *
 * @ORM\Table(name="entite_pol")
 * @ORM\Entity
 */
class EntitePol
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="entite_pol_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="num_iacp", type="smallint", nullable=true)
     */
    private $numIacp;

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

    public function getNumIacp(): ?int
    {
        return $this->numIacp;
    }

    public function setNumIacp(?int $numIacp): self
    {
        $this->numIacp = $numIacp;

        return $this;
    }


}
