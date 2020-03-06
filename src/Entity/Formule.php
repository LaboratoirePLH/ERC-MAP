<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formule
 *
 * @ORM\Table(name="formule")
 * @ORM\Entity
 */
class Formule extends AbstractEntity
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
     * @var int
     *
     * @ORM\Column(name="puissances_divines", type="smallint", nullable=true, options={"unsigned": true, "default":1})
     */
    private $puissancesDivines = 1;

    /**
     * @var \Attestation
     *
     * @ORM\ManyToOne(targetEntity="Attestation", inversedBy="formules")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="attestation_id", referencedColumnName="id")
     * })
     */
    private $attestation;

    /* Hack to allow the id field to be present in the form */
    public function setId(int $id): self
    {
        return $this;
    }

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

    public function getPuissancesDivines(): ?int
    {
        return $this->puissancesDivines;
    }

    public function setPuissancesDivines($puissancesDivines): self
    {
        if(!is_numeric($puissancesDivines)){
            $puissancesDivines = null;
        }
        $this->puissancesDivines = $puissancesDivines;

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

    public function __toString(): string
    {
        return $this->getFormule();
    }

    /**
     * Clone magic method
     */
    public function __clone()
    {
        if($this->id !== null){
            $this->id = null;
            $this->createur = null;
            $this->createurId = null;
        }
    }
}
