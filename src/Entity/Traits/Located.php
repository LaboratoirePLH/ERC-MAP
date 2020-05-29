<?php

namespace App\Entity\Traits;

use App\Entity\Localisation;

use Doctrine\ORM\Mapping as ORM;

trait Located
{
    use ShouldClearOrphanLocations;

    /**
     * @var bool
     *
     * @ORM\Column(name="est_localisee", type="boolean", nullable=true)
     */
    private $estLocalisee;

    public function getEstLocalisee(): ?bool
    {
        return $this->estLocalisee;
    }

    public function setEstLocalisee(?bool $estLocalisee): self
    {
        $this->estLocalisee = $estLocalisee;
        return $this;
    }

    /**
     * @var \Localisation|null
     *
     * @ORM\ManyToOne(targetEntity="Localisation", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinColumn(name="localisation_id", referencedColumnName="id", nullable=true)
     */
    private $localisation;

    public function getLocalisation(): ?Localisation
    {
        return $this->localisation;
    }

    public function setLocalisation(?Localisation $localisation): self
    {
        $this->localisation = $localisation;
        $this->setEstLocalisee($localisation !== null);
        return $this;
    }
}
