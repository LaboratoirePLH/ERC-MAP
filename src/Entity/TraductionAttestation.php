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

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_fr", type="string", length=1000, nullable=true)
     */
    private $nomFr;

    public function getNomFr(): ?string
    {
        return $this->nomFr;
    }

    public function setNomFr(?string $nomFr): self
    {
        $this->nomFr = $nomFr;
        return $this;
    }

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_en", type="string", length=1000, nullable=true)
     */
    private $nomEn;

    public function getNomEn(): ?string
    {
        return $this->nomEn;
    }

    public function setNomEn(?string $nomEn): self
    {
        $this->nomEn = $nomEn;
        return $this;
    }

    public function getNom(?string $lang): ?string
    {
        if($lang == 'fr'){
            return $this->nomFr;
        } else {
            return $this->nomEn;
        }
    }

    public function getTranslatedName(): array
    {
        return [
            'nomFr' => $this->getNomFr(),
            'nomEn' => $this->getNomEn()
        ];
    }

    public function setTranslatedName($data): self
    {
        $this->setNomFr($data['nomFr'] ?? null);
        $this->setNomEn($data['nomEn'] ?? null);
        return $this;
    }

    public function __toString(): string
    {
        return parent::__toString() . $this->getNomFr() . '/' . $this->getNomEn();
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

    public function toArray(): array
    {
        return array_merge(['id' => $this->id], $this->getTranslatedName());
    }
}
