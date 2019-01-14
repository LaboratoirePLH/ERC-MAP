<?php

namespace App\Entity\Traits;

trait TranslatedName
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
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
}