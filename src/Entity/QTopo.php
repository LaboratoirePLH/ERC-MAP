<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * QTopo
 *
 * @ORM\Table(name="q_topo")
 * @ORM\Entity
 */
class QTopo
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="q_topo_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

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
     * @ORM\ManyToMany(targetEntity="Localisation", inversedBy="idTopo")
     * @ORM\JoinTable(name="a_topo",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_topo", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_loc", referencedColumnName="id")
     *   }
     * )
     */
    private $idLoc;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idLoc = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Collection|Localisation[]
     */
    public function getIdLoc(): Collection
    {
        return $this->idLoc;
    }

    public function addIdLoc(Localisation $idLoc): self
    {
        if (!$this->idLoc->contains($idLoc)) {
            $this->idLoc[] = $idLoc;
        }

        return $this;
    }

    public function removeIdLoc(Localisation $idLoc): self
    {
        if ($this->idLoc->contains($idLoc)) {
            $this->idLoc->removeElement($idLoc);
        }

        return $this;
    }

}
