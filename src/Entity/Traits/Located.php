<?php

namespace App\Entity\Traits;

use App\Entity\Localisation;

use Doctrine\ORM\Mapping as ORM;

trait Located
{
    /**
     * @var bool
     */
    private $estLocalisee;

    public function getEstLocalisee(): bool
    {
        return !is_null($this->getLocalisation());
    }

    public function setEstLocalisee(bool $estLocalisee): self
    {
        if(!$estLocalisee){
            $this->setLocalisation(null);
        }
        return $this;
    }

    /**
     * @var \Localisation|null
     *
     * @ORM\OneToOne(targetEntity="Localisation", cascade={"persist"}, fetch="EAGER", orphanRemoval=true)
     * @ORM\JoinColumn(name="localisation_decouverte_id", referencedColumnName="id", nullable=true)
     */
    private $localisation;

    public function getLocalisation(): ?Localisation
    {
        return $this->localisation;
    }

    public function setLocalisation(?Localisation $localisation): self
    {
        $this->localisation = $localisation;
        return $this;
    }
}