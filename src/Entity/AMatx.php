<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * AMatx
 *
 * @ORM\Table(name="a_matx", indexes={@ORM\Index(name="IDX_5DFDEE73F9780F0D", columns={"id_cat_matx"})})
 * @ORM\Entity
 */
class AMatx
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_matx", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="a_matx_id_matx_seq", allocationSize=1, initialValue=1)
     */
    private $idMatx;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var \CatMatx
     *
     * @ORM\ManyToOne(targetEntity="CatMatx")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_cat_matx", referencedColumnName="id_cat_matx")
     * })
     */
    private $idCatMatx;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Attestation", mappedBy="idMat")
     */
    private $idAttest;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAttest = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdMatx(): ?int
    {
        return $this->idMatx;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdCatMatx(): ?CatMatx
    {
        return $this->idCatMatx;
    }

    public function setIdCatMatx(?CatMatx $idCatMatx): self
    {
        $this->idCatMatx = $idCatMatx;

        return $this;
    }

    /**
     * @return Collection|Attestation[]
     */
    public function getIdAttest(): Collection
    {
        return $this->idAttest;
    }

    public function addIdAttest(Attestation $idAttest): self
    {
        if (!$this->idAttest->contains($idAttest)) {
            $this->idAttest[] = $idAttest;
            $idAttest->addIdMat($this);
        }

        return $this;
    }

    public function removeIdAttest(Attestation $idAttest): self
    {
        if ($this->idAttest->contains($idAttest)) {
            $this->idAttest->removeElement($idAttest);
            $idAttest->removeIdMat($this);
        }

        return $this;
    }

}
