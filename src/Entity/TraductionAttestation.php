<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TraductionAttestation
 *
 * @ORM\Table(name="traduction_attestation")
 * @ORM\Entity
 */
class TraductionAttestation extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var \Attestation
     *
     * @ORM\ManyToOne(targetEntity="Attestation", inversedBy="traductions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_attestation", referencedColumnName="id")
     * })
     */
    private $attestation;

    public function getAttestation(): ?Attestation
    {
        return $this->attestation;
    }

    public function setAttestation(?Attestation $attestation): self
    {
        $this->attestation = $attestation;
        return $this;
    }

    public function __toString(): string
    {
        return parent::__toString() . $this->getNomFr() . '/' . $this->getNomEn();
    }
}
