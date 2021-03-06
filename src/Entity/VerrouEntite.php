<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VerrouEntiteRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class VerrouEntite extends AbstractEntity
{
    use Traits\EntityId;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Source", mappedBy="verrou", fetch="EAGER")
     */
    private $sources;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Element", mappedBy="verrou", fetch="EAGER")
     */
    private $elements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Biblio", mappedBy="verrou", fetch="EAGER")
     */
    private $biblios;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_fin;

    /**
     * @var Chercheur
     *
     * @ORM\ManyToOne(targetEntity="Chercheur", fetch="EAGER", inversedBy="verrous")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_creation_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $createur;

    public function __construct()
    {
        $this->sources = new ArrayCollection();
        $this->elements = new ArrayCollection();
        $this->biblios = new ArrayCollection();
    }

    /**
     * @return Collection|Source[]
     */
    public function getSources(): Collection
    {
        return $this->sources;
    }

    public function addSource(Source $source): self
    {
        if (!$this->sources->contains($source)) {
            $this->sources[] = $source;
            $source->setVerrou($this);
        }

        return $this;
    }

    public function removeSource(Source $source): self
    {
        if ($this->sources->contains($source)) {
            $this->sources->removeElement($source);
            // set the owning side to null (unless already changed)
            if ($source->getVerrou() === $this) {
                $source->setVerrou(null);
            }
        }

        return $this;
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
            $element->setVerrou($this);
        }

        return $this;
    }

    public function removeElement(Element $element): self
    {
        if ($this->elements->contains($element)) {
            $this->elements->removeElement($element);
            // set the owning side to null (unless already changed)
            if ($element->getVerrou() === $this) {
                $element->setVerrou(null);
            }
        }

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getCreateur(): ?Chercheur
    {
        return $this->createur;
    }

    public function setCreateur(?Chercheur $createur): self
    {
        $this->createur = $createur;
        return $this;
    }

    public function isWritable(Chercheur $chercheur): bool
    {
        return $this->getCreateur()->getId() === $chercheur->getId()
            || $this->getDateFin() < (new \DateTime);
    }

    /**
     * @ORM\PreRemove
     */
    public function releaseLockOnDelete()
    {
        foreach ($this->getSources() as $source) {
            $this->removeSource($source);
        }
        foreach ($this->getElements() as $element) {
            $this->removeElement($element);
        }
    }

    /**
     * @return Collection|Biblio[]
     */
    public function getBiblios(): Collection
    {
        return $this->biblios;
    }

    public function addBiblio(Biblio $biblio): self
    {
        if (!$this->biblios->contains($biblio)) {
            $this->biblios[] = $biblio;
            $biblio->setVerrou($this);
        }

        return $this;
    }

    public function removeBiblio(Biblio $biblio): self
    {
        if ($this->biblios->contains($biblio)) {
            $this->biblios->removeElement($biblio);
            // set the owning side to null (unless already changed)
            if ($biblio->getVerrou() === $this) {
                $biblio->setVerrou(null);
            }
        }

        return $this;
    }

    public function toArray(string $format = null)
    {
        $date_fin = $this->date_fin;
        if ($format !== null) {
            $date_fin = $date_fin->format($format);
        }
        return [
            'createur' => $this->createur->getPrenomNom(),
            'date_fin' => $date_fin
        ];
    }
}
