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
class Element extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\Indexed;
    use Traits\Located;
    use Traits\Tracked;
    use Traits\Translatable;
    use Traits\TranslatedComment;

    /**
     * @var string|null
     *
     * @ORM\Column(name="etat_absolu", type="text", nullable=true)
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
     * @ORM\OrderBy({"id" = "ASC"})
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
     * @ORM\OneToMany(targetEntity="ElementBiblio", mappedBy="element", orphanRemoval=true)
     */
    private $elementBiblios;

    /**
     * @var bool
     *
     * @ORM\Column(name="a_reference", type="boolean", nullable=true)
     */
    private $aReference;

    /**
     * @ORM\ManyToMany(targetEntity="Element")
     * @ORM\JoinTable(name="theonymes_implicites",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_parent", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_enfant", referencedColumnName="id")
     *   }
     * )
     */
    private $theonymesImplicites;

    /**
     * @ORM\ManyToMany(targetEntity="Element")
     * @ORM\JoinTable(name="theonymes_construits",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_parent", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_enfant", referencedColumnName="id")
     *   }
     * )
     */
    private $theonymesConstruits;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\VerrouEntite", inversedBy="elements", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="verrou_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * })
     */
    private $verrou;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->elementBiblios = new ArrayCollection();
        $this->theonymesImplicites = new ArrayCollection();
        $this->theonymesConstruits = new ArrayCollection();
        $this->contientElements = new ArrayCollection();
        $this->traductions = new ArrayCollection();
    }

    /**
     * Clone magic method
     */
    public function __clone()
    {
        if ($this->id) {
            $this->id = null;

            // Reset tracking fields
            $this->dateCreation     = null;
            $this->dateModification = null;
            $this->createur         = null;
            $this->dernierEditeur   = null;
            $this->version          = null;
            $this->verrou           = null;

            // Clone localisation
            if ($this->localisation !== null) {
                $this->localisation = clone $this->localisation;
            }

            // Clone Traductions
            $cloneTraductions = new ArrayCollection();
            foreach ($this->traductions as $t) {
                $cloneT = clone $t;
                $cloneT->setElement($this);
                $cloneTraductions->add($cloneT);
            }
            $this->traductions = $cloneTraductions;

            // Clone elementBiblios
            $cloneElementBiblios = new ArrayCollection();
            foreach ($this->elementBiblios as $eb) {
                $cloneEb = clone $eb;
                $cloneEb->setElement($this);
                $cloneElementBiblios->add($cloneEb);
            }
            $this->elementBiblios = $cloneElementBiblios;

            // Do not clone contextual data, theonymes implicites and theonymes construits
            $this->contientElements    = new ArrayCollection();
            $this->theonymesImplicites = new ArrayCollection();
            $this->theonymesConstruits = new ArrayCollection();
        }
    }

    // Hack for elementMini form
    public function setId(int $id): self
    {
        return $this;
    }

    public function getAffichage(): string
    {
        return sprintf("#%d : %s (%s)", $this->getId(), strip_tags($this->getEtatAbsolu()), $this->getBetaCode());
    }

    public function getEtatAbsolu(): ?string
    {
        return $this->sanitizeWysiwygString($this->sanitizeOpenXMLString($this->etatAbsolu));
    }

    public function setEtatAbsolu(?string $etatAbsolu): self
    {
        $this->etatAbsolu = $this->sanitizeWysiwygString($this->sanitizeOpenXMLString($etatAbsolu));
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
        if (empty($this->getCategories())) {
            return "";
        }
        $names = $this->getCategories()->map(function ($cat) use ($lang) {
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
        if (empty($this->getTraductions())) {
            return "";
        }
        $names = $this->getTraductions()->map(function ($trad) use ($lang) {
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
    public function _clearTheonymes()
    {
        if (!$this->getAReference()) {
            $this->getTheonymesConstruits()->clear();
            $this->getTheonymesImplicites()->clear();
        }
    }

    /**
     * @return Collection|Element[]
     */
    public function getTheonymesImplicites(): Collection
    {
        return $this->theonymesImplicites;
    }

    public function addTheonymesImplicite(Element $theonymesImplicite): self
    {
        if (!$this->theonymesImplicites->contains($theonymesImplicite)) {
            $this->theonymesImplicites[] = $theonymesImplicite;
        }

        return $this;
    }

    public function removeTheonymesImplicite(Element $theonymesImplicite): self
    {
        if ($this->theonymesImplicites->contains($theonymesImplicite)) {
            $this->theonymesImplicites->removeElement($theonymesImplicite);
        }

        return $this;
    }

    /**
     * @return Collection|Element[]
     */
    public function getTheonymesConstruits(): Collection
    {
        return $this->theonymesConstruits;
    }

    public function addTheonymesConstruit(Element $theonymesConstruit): self
    {
        if (!$this->theonymesConstruits->contains($theonymesConstruit)) {
            $this->theonymesConstruits[] = $theonymesConstruit;
        }

        return $this;
    }

    public function removeTheonymesConstruit(Element $theonymesConstruit): self
    {
        if ($this->theonymesConstruits->contains($theonymesConstruit)) {
            $this->theonymesConstruits->removeElement($theonymesConstruit);
        }

        return $this;
    }

    public function getVerrou(): ?VerrouEntite
    {
        return $this->verrou;
    }

    public function setVerrou(?VerrouEntite $verrou): self
    {
        $this->verrou = $verrou;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id'             => $this->id,
            'etatAbsolu'     => $this->etatAbsolu,
            'betaCode'       => $this->betaCode,
            'traductions'    => $this->traductions->map(function ($entry) {
                return $entry->getTranslatedName();
            })->getValues(),
            'natureElement'  => $this->natureElement === null ? null : $this->natureElement->toArray(),
            'categories'     => $this->categories->map(function ($entry) {
                return $entry->toArray();
            })->getValues(),
            'localisation'   => $this->localisation === null ? null : $this->localisation->toArray(),
            'elementBiblios' => $this->elementBiblios->map(function ($eb) {
                return array_merge($eb->getBiblio()->toArray(), [
                    'reference'         => $eb->getReferenceElement()
                ]);
            })->getValues(),
            'commentaireFr' => $this->commentaireFr,
            'commentaireEn' => $this->commentaireEn
        ];
    }
}
