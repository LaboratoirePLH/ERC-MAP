<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AttestationMateriel
 *
 * @ORM\Table(name="attestation_materiel")
 * @ORM\Entity
 */
class AttestationMateriel extends AbstractEntity
{
    use Traits\EntityId;

    /**
     * @var \Attestation
     *
     * @ORM\ManyToOne(targetEntity="Attestation", inversedBy="attestationMateriels", fetch="EXTRA_LAZY")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_attestation", referencedColumnName="id")
     * })
     */
    private $attestation;

    /**
     * @var \Materiel
     *
     * @ORM\ManyToOne(targetEntity="Materiel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_materiel", referencedColumnName="id")
     * })
     */
    private $materiel;

    /**
     * @var \CategorieMateriel
     *
     * @ORM\ManyToOne(targetEntity="CategorieMateriel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie_materiel", referencedColumnName="id")
     * })
     */
    private $categorieMateriel;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="quantite", type="smallint", nullable=true)
     */
    private $quantite = 1;

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

    public function getCategorieMateriel(): ?CategorieMateriel
    {
        return $this->categorieMateriel;
    }

    public function setCategorieMateriel(?CategorieMateriel $categorieMateriel): self
    {
        $this->categorieMateriel = $categorieMateriel;
        return $this;
    }

    public function setTypeCategorieMateriel($data): self
    {
        $this->setMateriel($data['materiel'] ?? null);
        $this->setCategorieMateriel($data['categorieMateriel'] ?? null);
        return $this;
    }

    public function getTypeCategorieMateriel(): array
    {
        return [
            'materiel' => $this->getMateriel() ?? null,
            'categorieMateriel' => $this->getCategorieMateriel() ?? null,
        ];
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

    public function __toString(): string
    {
        return implode(" / ", [
            "Attestation #" . $this->getAttestation()->getId(),
            "Catégoriel Matériel #" . $this->getCategorieMateriel()->getId() ?? "",
            "Matériel #" . $this->getMateriel()->getId() ?? ""
        ]);
    }

    /**
     * Clone magic method
     */
    public function __clone()
    {
        if($this->id !== null){
            $this->id = null;
        }
    }
}
