<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AttestationMateriel
 *
 * @ORM\Table(name="attestation_materiel")
 * @ORM\Entity
 */
class AttestationMateriel
{
    /**
     * @var \Attestation
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Attestation", inversedBy="attestationMateriels", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_attestation", referencedColumnName="id")
     * })
     */
    private $attestation;

    /**
     * @var \Materiel
     *
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Materiel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_materiel", referencedColumnName="id")
     * })
     */
    private $materiel;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="quantite", type="smallint", nullable=true)
     */
    private $quantite;


    public function getAttestation(): ?Attestation
    {
        return $this->attestation;
    }

    public function setAttestation(?Attestation $attestation): self
    {
        $this->attestation = $attestation;
        return $this;
    }

    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): self
    {
        $this->materiel = $materiel;
        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(?int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }
}
