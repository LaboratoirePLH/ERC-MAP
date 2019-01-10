<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Genre
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity
 */
class Genre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_genre", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="genre_id_genre_seq", allocationSize=1, initialValue=1)
     */
    private $idGenre;

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
     * @ORM\ManyToMany(targetEntity="Agent", inversedBy="idGenre")
     * @ORM\JoinTable(name="agent_genre",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_genre", referencedColumnName="id_genre")
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

    public function getIdGenre(): ?int
    {
        return $this->idGenre;
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
