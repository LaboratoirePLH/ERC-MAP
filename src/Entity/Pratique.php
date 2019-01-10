<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pratique
 *
 * @ORM\Table(name="pratique")
 * @ORM\Entity
 */
class Pratique
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_pratique", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="pratique_id_pratique_seq", allocationSize=1, initialValue=1)
     */
    private $idPratique;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Attestation", inversedBy="idPratique")
     * @ORM\JoinTable(name="a_pratique",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_pratique", referencedColumnName="id_pratique")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_attest", referencedColumnName="id")
     *   }
     * )
     */
    private $idAttest;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAttest = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdPratique(): ?int
    {
        return $this->idPratique;
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
        }

        return $this;
    }

    public function removeIdAttest(Attestation $idAttest): self
    {
        if ($this->idAttest->contains($idAttest)) {
            $this->idAttest->removeElement($idAttest);
        }

        return $this;
    }

}
