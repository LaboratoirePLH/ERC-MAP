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
     * @ORM\OneToMany(targetEntity="App\Entity\Source", mappedBy="verrou")
     */
    private $sources;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Attestation", mappedBy="verrou")
     */
    private $attestations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Element", mappedBy="verrou")
     */
    private $elements;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_fin;

    /**
     * @var \Chercheur
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
        $this->attestations = new ArrayCollection();
        $this->elements = new ArrayCollection();
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
     * @return Collection|Attestation[]
     */
    public function getAttestations(): Collection
    {
        return $this->attestations;
    }

    public function addAttestation(Attestation $attestation): self
    {
        if (!$this->attestations->contains($attestation)) {
            $this->attestations[] = $attestation;
            $attestation->setVerrou($this);
        }

        return $this;
    }

    public function removeAttestation(Attestation $attestation): self
    {
        if ($this->attestations->contains($attestation)) {
            $this->attestations->removeElement($attestation);
            // set the owning side to null (unless already changed)
            if ($attestation->getVerrou() === $this) {
                $attestation->setVerrou(null);
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
        return $this->getCreateur()->getId() === $chercheur->getId();
    }

    /**
     * @ORM\PreRemove
     */
    public function releaseLockOnDelete()
    {
        foreach($this->getSources() as $source){ $this->removeSource($source); }
        foreach($this->getAttestations() as $attestation){ $this->removeAttestation($attestation); }
        foreach($this->getElements() as $element){ $this->removeElement($element); }
    }
}
