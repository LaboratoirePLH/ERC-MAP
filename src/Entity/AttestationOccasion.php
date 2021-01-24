<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AttestationOccasion
 *
 * @ORM\Table(name="attestation_occasion")
 * @ORM\Entity
 */
class AttestationOccasion extends AbstractEntity
{
    use Traits\EntityId;

    /**
     * @var Attestation
     *
     * @ORM\ManyToOne(targetEntity="Attestation", inversedBy="attestationOccasions", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_attestation", referencedColumnName="id")
     * })
     */
    private $attestation;

    /**
     * @var Occasion
     *
     * @ORM\ManyToOne(targetEntity="Occasion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_occasion", referencedColumnName="id")
     * })
     */
    private $occasion;

    /**
     * @var CategorieOccasion
     *
     * @ORM\ManyToOne(targetEntity="CategorieOccasion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie_occasion", referencedColumnName="id")
     * })
     */
    private $categorieOccasion;

    public function getAttestation(): ?Attestation
    {
        return $this->attestation;
    }

    public function setAttestation(?Attestation $attestation): self
    {
        $this->attestation = $attestation;
        return $this;
    }

    public function getOccasion(): ?Occasion
    {
        return $this->occasion;
    }

    public function setOccasion(?Occasion $occasion): self
    {
        $this->occasion = $occasion;
        return $this;
    }

    public function getCategorieOccasion(): ?CategorieOccasion
    {
        return $this->categorieOccasion;
    }

    public function setCategorieOccasion(?CategorieOccasion $categorieOccasion): self
    {
        $this->categorieOccasion = $categorieOccasion;
        return $this;
    }

    public function setTypeCategorieOccasion($data): self
    {
        $this->setOccasion($data['occasion'] ?? null);
        $this->setCategorieOccasion($data['categorieOccasion'] ?? null);
        return $this;
    }

    public function getTypeCategorieOccasion(): array
    {
        return [
            'occasion' => $this->getOccasion() ?? null,
            'categorieOccasion' => $this->getCategorieOccasion() ?? null,
        ];
    }

    public function __toString(): string
    {
        return implode(" / ", [
            "Attestation #" . $this->getAttestation()->getId(),
            "Catégorie Occasion #" . $this->getCategorieOccasion()->getId() ?? "",
            "Occasion #" . $this->getOccasion()->getId() ?? ""
        ]);
    }

    /**
     * Clone magic method
     */
    public function __clone()
    {
        if ($this->id !== null) {
            $this->id = null;
        }
    }
}
