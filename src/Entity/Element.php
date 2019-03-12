<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Element
 *
 * @ORM\Table(name="element")
 * @ORM\Entity
 */
class Element
{
    use Traits\EntityId;
    use Traits\Located;
    use Traits\Translatable;
    use Traits\TranslatedComment;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat_absolu", type="string", length=255, nullable=true)
     */
    private $etatAbsolu;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat_morphologique", type="string", length=255, nullable=true)
     */
    private $etatMorphologique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="beta_code", type="string", length=255, nullable=true)
     */
    private $betaCode;

    /**
     * @var \NatureElement
     *
     * @ORM\ManyToOne(targetEntity="NatureElement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_nature_element", referencedColumnName="id")
     * })
     */
    private $natureElement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="CategorieElement", mappedBy="elements")
     */
    private $categories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ContientElement", mappedBy="element")
     */
    private $contientElements;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ElementBiblio", mappedBy="element")
     */
    private $elementBiblios;

    /**
     * @ORM\ManyToMany(targetEntity="Element", inversedBy="theonymesParents")
     * @ORM\JoinTable(name="elements_theonymes",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_parent", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_enfant", referencedColumnName="id")
     *   }
     * )
     */
    private $theonymesEnfants;

    /**
     * @ORM\ManyToMany(targetEntity="Element", mappedBy="theonymesEnfants")
     */
    private $theonymesParents;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->elementBiblios = new ArrayCollection();
        $this->theonymesEnfants = new ArrayCollection();
        $this->theonymesParents = new ArrayCollection();
        $this->contientElements = new ArrayCollection();
    }

    public function getEtatAbsolu(): ?string
    {
        return $this->etatAbsolu;
    }

    public function setEtatAbsolu(?string $etatAbsolu): self
    {
        $this->etatAbsolu = $etatAbsolu;
        return $this;
    }

    public function getEtatMorphologique(): ?string
    {
        return $this->etatMorphologique;
    }

    public function setEtatMorphologique(?string $etatMorphologique): self
    {
        $this->etatMorphologique = $etatMorphologique;
        return $this;
    }

    public function getBetaCode(): ?string
    {
        return $this->betaCode;
    }

    public function setBetaCode(?string $betaCode): self
    {
        $this->betaCode = $betaCode;
        return $this;
    }

    public function getNatureElement(): ?NatureElement
    {
        return $this->natureElement;
    }

    public function setNatureElement(?NatureElement $natureElement): self
    {
        $this->natureElement = $natureElement;
        return $this;
    }

    /**
     * @return Collection|CategorieElement[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(CategorieElement $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->addElement($this);
        }
        return $this;
    }

    public function removeCategory(CategorieElement $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            $category->removeElement($this);
        }
        return $this;
    }

    /**
     * @return Collection|ElementBiblio[]
     */
    public function getElementBiblios(): Collection
    {
        return $this->elementBiblios;
    }

    public function addElementBiblio(ElementBiblio $elementBiblio): self
    {
        if (!$this->elementBiblios->contains($elementBiblio)) {
            $this->elementBiblios[] = $elementBiblio;
            $elementBiblio->setElement($this);
        }
        return $this;
    }

    public function removeElementBiblio(ElementBiblio $elementBiblio): self
    {
        if ($this->elementBiblios->contains($elementBiblio)) {
            $this->elementBiblios->removeElement($elementBiblio);
            // set the owning side to null (unless already changed)
            if ($elementBiblio->getElement() === $this) {
                $elementBiblio->setElement(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|Element[]
     */
    public function getTheonymesEnfants(): Collection
    {
        return $this->theonymesEnfants;
    }

    public function addTheonymesEnfant(Element $theonymesEnfant): self
    {
        if (!$this->theonymesEnfants->contains($theonymesEnfant)) {
            $this->theonymesEnfants[] = $theonymesEnfant;
        }
        return $this;
    }

    public function removeTheonymesEnfant(Element $theonymesEnfant): self
    {
        if ($this->theonymesEnfants->contains($theonymesEnfant)) {
            $this->theonymesEnfants->removeElement($theonymesEnfant);
        }
        return $this;
    }

    /**
     * @return Collection|Element[]
     */
    public function getTheonymesParents(): Collection
    {
        return $this->theonymesParents;
    }

    public function addTheonymesParent(Element $theonymesParent): self
    {
        if (!$this->theonymesParents->contains($theonymesParent)) {
            $this->theonymesParents[] = $theonymesParent;
            $theonymesParent->addTheonymesEnfant($this);
        }
        return $this;
    }

    public function removeTheonymesParent(Element $theonymesParent): self
    {
        if ($this->theonymesParents->contains($theonymesParent)) {
            $this->theonymesParents->removeElement($theonymesParent);
            $theonymesParent->removeTheonymesEnfant($this);
        }
        return $this;
    }

    /**
     * @return Collection|ContientElement[]
     */
    public function getContientElements(): Collection
    {
        return $this->contientElements;
    }

    public function addContientElement(ContientElement $contientElement): self
    {
        if (!$this->contientElements->contains($contientElement)) {
            $this->contientElements[] = $contientElement;
            $contientElement->setElement($this);
        }

        return $this;
    }

    public function removeContientElement(ContientElement $contientElement): self
    {
        if ($this->contientElements->contains($contientElement)) {
            $this->contientElements->removeElement($contientElement);
            // set the owning side to null (unless already changed)
            if ($contientElement->getElement() === $this) {
                $contientElement->setElement(null);
            }
        }

        return $this;
    }
}
