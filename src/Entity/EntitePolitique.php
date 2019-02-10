<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntitePolitique
 *
 * @ORM\Table(name="entite_politique")
 * @ORM\Entity
 */
class EntitePolitique
{
    use Traits\TranslatedName;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="numero_iacp", type="smallint", nullable=true)
     */
    private $numeroIacp;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getNumeroIacp(): ?int
    {
        return $this->numeroIacp;
    }

    public function setNumeroIacp(?int $numeroIacp): self
    {
        $this->numeroIacp = $numeroIacp;

        return $this;
    }

    public function getAffichageFr(){
        return \sprintf("%s (IACP: %s)", $this->getNomFr(), $this->getNumeroIacp() ?? "?");
    }

    public function getAffichageEn(){
        return \sprintf("%s (IACP: %s)", $this->getNomEn(), $this->getNumeroIacp() ?? "?");
    }


}
