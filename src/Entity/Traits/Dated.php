<?php

namespace App\Entity\Traits;

use App\Entity\Datation;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait Dated
{
    /**
     * @var bool|null
     *
     * @ORM\Column(name="est_datee", type="boolean", nullable=true)
     */
    private $estDatee;

    public function getEstDatee(): ?bool
    {
        return $this->estDatee;
    }

    public function setEstDatee(?bool $estDatee): self
    {
        $this->estDatee = $estDatee;
        return $this;
    }

    /**
     * @var Datation
     *
     * @ORM\OneToOne(targetEntity="Datation", cascade={"persist"}, fetch="EAGER", orphanRemoval=true)
     * @ORM\JoinColumn(name="datation_id", referencedColumnName="id", nullable=true)
     * @Assert\Valid
     */
    private $datation;

    public function getDatation(): ?Datation
    {
        return $this->datation;
    }

    public function setDatation(?Datation $datation): self
    {
        $this->datation = $datation;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function _clearOrphanDatation()
    {
        if (!$this->getEstDatee()) {
            $this->setDatation(null);
        }
    }
}
