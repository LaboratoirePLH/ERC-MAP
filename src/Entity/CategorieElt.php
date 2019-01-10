<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieElt
 *
 * @ORM\Table(name="categorie_elt")
 * @ORM\Entity
 */
class CategorieElt
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_cat_elt", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="categorie_elt_id_cat_elt_seq", allocationSize=1, initialValue=1)
     */
    private $idCatElt;

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
     * @ORM\ManyToMany(targetEntity="Elements", inversedBy="idCatElt")
     * @ORM\JoinTable(name="a_catgeorie",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_cat_elt", referencedColumnName="id_cat_elt")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_elt", referencedColumnName="id")
     *   }
     * )
     */
    private $idElt;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idElt = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdCatElt(): ?int
    {
        return $this->idCatElt;
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
     * @return Collection|Elements[]
     */
    public function getIdElt(): Collection
    {
        return $this->idElt;
    }

    public function addIdElt(Elements $idElt): self
    {
        if (!$this->idElt->contains($idElt)) {
            $this->idElt[] = $idElt;
        }

        return $this;
    }

    public function removeIdElt(Elements $idElt): self
    {
        if ($this->idElt->contains($idElt)) {
            $this->idElt->removeElement($idElt);
        }

        return $this;
    }

}
