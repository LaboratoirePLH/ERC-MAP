<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formule
 *
 * @ORM\Table(name="formule")
 * @ORM\Entity
 */
class Formule
{
    use Traits\EntityId;
    use Traits\Created;

    /**
     * @var string
     *
     * @ORM\Column(name="formule", type="text", nullable=false)
     */
    private $formule;

    /**
     * @var int
     *
     * @ORM\Column(name="position_formule", type="smallint", nullable=false)
     */
    private $positionFormule;

    /**
     * @var \Attestation
     *
     * @ORM\ManyToOne(targetEntity="Attestation", inversedBy="formules")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attestation_id", referencedColumnName="id")
     * })
     */
    private $attestation;

    public function getFormule(): ?string
    {
        return $this->formule;
    }

    public function setFormule(string $formule): self
    {
        $this->formule = $formule;

        return $this;
    }

    public function getPositionFormule(): ?int
    {
        return $this->positionFormule;
    }

    public function setPositionFormule(int $positionFormule): self
    {
        $this->positionFormule = $positionFormule;

        return $this;
    }

    public function getAttestation(): ?Attestation
    {
        return $this->attestation;
    }

    public function setAttestation(?Attestation $attestation): self
    {
        $this->attestation = $attestation;

        return $this;
    }

}
