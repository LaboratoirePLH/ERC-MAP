<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Translatable
{
    /**
     * @var bool|null
     *
     * @ORM\Column(name="traduire_fr", type="boolean", nullable=true)
     */
    private $traduireFr = true;

    public function getTraduireFr(): ?bool
    {
        return $this->traduireFr;
    }

    public function setTraduireFr(?bool $traduireFr): self
    {
        $this->traduireFr = $traduireFr;
        return $this;
    }

    /**
     * @var bool|null
     *
     * @ORM\Column(name="traduire_en", type="boolean", nullable=true)
     */
    private $traduireEn = true;

    public function getTraduireEn(): ?bool
    {
        return $this->traduireEn;
    }

    public function setTraduireEn(?bool $traduireEn): self
    {
        $this->traduireEn = $traduireEn;
        return $this;
    }

    public function getATraduire(?bool $lang): ?bool
    {
        if($lang == 'fr'){
            return $this->traduireFr;
        } else {
            return $this->traduireEn;
        }
    }
}