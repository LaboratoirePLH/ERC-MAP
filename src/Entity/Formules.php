<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formules
 *
 * @ORM\Table(name="formules", indexes={@ORM\Index(name="IDX_E5BA88E1A1342FAE", columns={"id_attest"}), @ORM\Index(name="IDX_E5BA88E11DE72777", columns={"id_chercheur"})})
 * @ORM\Entity
 */
class Formules
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_formule", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="formules_id_formule_seq", allocationSize=1, initialValue=1)
     */
    private $idFormule;

    /**
     * @var string|null
     *
     * @ORM\Column(name="formule", type="text", nullable=true)
     */
    private $formule;

    /**
     * @var int|null
     *
     * @ORM\Column(name="position_form", type="smallint", nullable=true)
     */
    private $positionForm;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="fiab_form", type="boolean", nullable=true)
     */
    private $fiabForm;

    /**
     * @var \Attestation
     *
     * @ORM\ManyToOne(targetEntity="Attestation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_attest", referencedColumnName="id")
     * })
     */
    private $idAttest;

    /**
     * @var \Chercheur
     *
     * @ORM\ManyToOne(targetEntity="Chercheur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_chercheur", referencedColumnName="id")
     * })
     */
    private $idChercheur;

    public function getIdFormule(): ?int
    {
        return $this->idFormule;
    }

    public function getFormule(): ?string
    {
        return $this->formule;
    }

    public function setFormule(?string $formule): self
    {
        $this->formule = $formule;

        return $this;
    }

    public function getPositionForm(): ?int
    {
        return $this->positionForm;
    }

    public function setPositionForm(?int $positionForm): self
    {
        $this->positionForm = $positionForm;

        return $this;
    }

    public function getFiabForm(): ?bool
    {
        return $this->fiabForm;
    }

    public function setFiabForm(?bool $fiabForm): self
    {
        $this->fiabForm = $fiabForm;

        return $this;
    }

    public function getIdAttest(): ?Attestation
    {
        return $this->idAttest;
    }

    public function setIdAttest(?Attestation $idAttest): self
    {
        $this->idAttest = $idAttest;

        return $this;
    }

    public function getIdChercheur(): ?Chercheur
    {
        return $this->idChercheur;
    }

    public function setIdChercheur(?Chercheur $idChercheur): self
    {
        $this->idChercheur = $idChercheur;

        return $this;
    }


}
