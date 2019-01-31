<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieElement
 *
 * @ORM\Table(name="categorie_elt")
 * @ORM\Entity
 */
class CategorieElement
{
    use Traits\TranslatedName;

    /**
     * @var int
     *
     * @ORM\Column(name="id_cat_elt", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="categorie_elt_id_cat_elt_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Element", inversedBy="categories")
     * @ORM\JoinTable(name="a_catgeorie",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_cat_elt", referencedColumnName="id_cat_elt")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_elt", referencedColumnName="id")
     *   }
     * )
     */
    private $elements;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->elements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Element[]
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    public function addElement(Element $element): self
    {
        if (!$this->elements->contains($element)) {
            $this->elements[] = $element;
        }

        return $this;
    }

    public function removeElement(Element $element): self
    {
        if ($this->elements->contains($element)) {
            $this->elements->removeElement($element);
        }

        return $this;
    }

}
