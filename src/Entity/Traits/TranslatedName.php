<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TranslatedName
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_fr", type="string", length=255, nullable=true)
     */
    private $nomFr;

    public function getNomFr(): ?string
    {
        return $this->nomFr;
    }

    public function setNomFr(?string $nomFr): self
    {
        $this->nomFr = $nomFr;
        return $this;
    }

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_en", type="string", length=255, nullable=true)
     */
    private $nomEn;

    public function getNomEn(): ?string
    {
        return $this->nomEn;
    }

    public function setNomEn(?string $nomEn): self
    {
        $this->nomEn = $nomEn;
        return $this;
    }

    public function getNom(?string $lang): ?string
    {
        if($lang == 'fr'){
            return $this->nomFr;
        } else {
            return $this->nomEn;
        }
    }

    public function getTranslatedName(): array
    {
        return [
            'nomFr' => $this->getNomFr(),
            'nomEn' => $this->getNomEn()
        ];
    }

    public function setTranslatedName($data): self
    {
        $this->setNomFr($data['nomFr'] ?? null);
        $this->setNomEn($data['nomEn'] ?? null);
        return $this;
    }

    public function __toString(): string
    {
        return $this->getNomFr();
    }
}