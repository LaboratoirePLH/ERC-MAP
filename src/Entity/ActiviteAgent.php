<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ActiviteAgent
 *
 * @ORM\Table(name="activite_agent")
 * @ORM\Entity
 */
class ActiviteAgent
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_activite", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="activite_agent_id_activite_seq", allocationSize=1, initialValue=1)
     */
    private $idActivite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=true)
     */
    private $name;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Agent", inversedBy="idActivite")
     * @ORM\JoinTable(name="agent_activite",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_activite", referencedColumnName="id_activite")
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

    public function getIdActivite(): ?int
    {
        return $this->idActivite;
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
