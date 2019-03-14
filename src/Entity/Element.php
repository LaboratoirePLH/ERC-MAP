<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Element
 *
 * @ORM\Table(name="element")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Element
{
    use Traits\EntityId;
    use Traits\Located;
    use Traits\Tracked;
    use Traits\Translatable;
    use Traits\TranslatedComment;

    public function __toString(){
        return 'toto';
    }

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat_absolu", type="string", length=255, nullable=true)
     */
    private $etatAbsolu;

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
     * @ORM\ManyToMany(targetEntity="CategorieElement")
     * @ORM\JoinTable(name="element_categorie",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_element", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_categorie_element", referencedColumnName="id")
     *   }
     * )
     * @Assert\Expression(
     *      "this.getCategories().count() <= 3",
     *      message="categories_count"
     * )
     */
    private $categories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TraductionElement", mappedBy="element", cascade={"persist", "remove"})
     */
    private $traductions;

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
     * @var bool
     *
     * @ORM\Column(name="a_reference", type="boolean", nullable=true)
     */
    private $aReference;

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
     * @ORM\ManyToMany(targetEntity="Element", mappedBy="theonymesEnfants", fetch="EAGER")
     */
    private $theonymesParents;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->elementBiblios = new ArrayCollection();
        $this->theonymesEnfants = new ArrayCollection();
        $this->theonymesParents = new ArrayCollection();
        $this->contientElements = new ArrayCollection();
        $this->traductions = new ArrayCollection();
    }

    // Hack for elementMini form
    public function setId(int $id): self
    {
        return $this;
    }

    public function getAffichage(): string
    {
        return sprintf("#%d : %s (%s)", $this->getId(), $this->getEtatAbsolu(), $this->getBetaCode());
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

    public function concatCategories($lang): string
    {
        if(empty($this->getCategories())){ return ""; }
        $names = $this->getCategories()->map(function($cat) use ($lang) {
            return $cat->getNom($lang);
        });
        $names = $names->toArray();
        sort($names);
        return implode('<br/>', $names);
    }

    public function addCategory(CategorieElement $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }
        return $this;
    }

    public function removeCategory(CategorieElement $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
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
            $theonymesParent->setAReference(true);
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
            $theonymesEnfant->setAReference(true);
            $this->setAReference(true);
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

    /**
     * @return Collection|TraductionElement[]
     */
    public function getTraductions(): ?Collection
    {
        return $this->traductions;
    }

    public function concatTraductions($lang): string
    {
        if(empty($this->getTraductions())){ return ""; }
        $names = $this->getTraductions()->map(function($trad) use ($lang) {
            return $trad->getNom($lang) ?? "?";
        });
        $names = $names->toArray();
        sort($names);
        return implode('<br>', $names);
    }


    public function addTraduction(TraductionElement $traduction = null): self
    {
        if (!is_null($traduction) && !$this->traductions->contains($traduction)) {
            $this->traductions[] = $traduction;
            $traduction->setElement($this);
        }
        return $this;
    }

    public function removeTraduction(TraductionElement $traduction = null): self
    {
        if (!is_null($traduction) && $this->traductions->contains($traduction)) {
            $this->traductions->removeElement($traduction);
            // set the owning side to null (unless already changed)
            if ($traduction->getElement() === $this) {
                $traduction->setElement(null);
            }
        }
        return $this;
    }

    public function getAReference(): ?bool
    {
        return $this->aReference;
    }

    public function setAReference(?bool $aReference): self
    {
        $this->aReference = $aReference;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function _clearTheonymes(){
        if(!$this->getAReference()){
            $this->getTheonymesEnfants()->clear();
            $this->getTheonymesParents()->clear();
        }
    }
}
