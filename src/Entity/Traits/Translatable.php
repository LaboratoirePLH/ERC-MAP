<?php

namespace App\Entity\Traits;

trait Translatable
{
    /**
     * @var bool|null
     *
     * @ORM\Column(name="a_traduire", type="boolean", nullable=true)
     */
    private $traduireFr;

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
     * @ORM\Column(name="to_translate", type="boolean", nullable=true)
     */
    private $traduireEn;

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