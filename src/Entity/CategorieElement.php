<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieElement
 *
 * @ORM\Table(name="categorie_element")
 * @ORM\Entity
 */
class CategorieElement
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Element", inversedBy="categories")
     * @ORM\JoinTable(name="element_categorie",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_categorie_element", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_element", referencedColumnName="id")
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
