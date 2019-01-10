<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * StatuAff
 *
 * @ORM\Table(name="statu_aff")
 * @ORM\Entity
 */
class StatuAff
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="statu_aff_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="text", nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="text", nullable=true)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Agent", inversedBy="idStatut")
     * @ORM\JoinTable(name="a_statut",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_statut", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_agent", referencedColumnName="id")
     *   }
     * )
     */
    private $idAgent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idAgent = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @return Collection|Agent[]
     */
    public function getIdAgent(): Collection
    {
        return $this->idAgent;
    }

    public function addIdAgent(Agent $idAgent): self
    {
        if (!$this->idAgent->contains($idAgent)) {
            $this->idAgent[] = $idAgent;
        }

        return $this;
    }

    public function removeIdAgent(Agent $idAgent): self
    {
        if ($this->idAgent->contains($idAgent)) {
            $this->idAgent->removeElement($idAgent);
        }

        return $this;
    }

}
