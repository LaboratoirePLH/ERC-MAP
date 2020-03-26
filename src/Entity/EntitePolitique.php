<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntitePolitique
 *
 * @ORM\Table(name="entite_politique")
 * @ORM\Entity
 */
class EntitePolitique extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var int|null
     *
     * @ORM\Column(name="numero_iacp", type="smallint", nullable=true)
     */
    private $numeroIacp;

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

    public function toArray(): array {
        return array_merge(['id' => $this->id], $this->getTranslatedName(), ['numeroIacp' => $this->numeroIacp]);
    }

}
