<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Agent
 *
 * @ORM\Table(name="agent")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Agent extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\Located;
    use Traits\TranslatedComment;

    /**
     * @var string|null
     *
     * @ORM\Column(name="designation", type="text", nullable=true)
     */
    private $designation;

    /**
     * @var \Attestation
     *
     * @ORM\ManyToOne(targetEntity="Attestation", inversedBy="agents")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_attestation", referencedColumnName="id")
     * })
     */
    private $attestation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="StatutAffiche")
     * @ORM\JoinTable(name="agent_statut",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_agent", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_statut", referencedColumnName="id")
     *   }
     * )
     */
    private $statutAffiches;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Nature")
     * @ORM\JoinTable(name="agent_nature",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_agent", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_nature", referencedColumnName="id")
     *   }
     * )
     */
    private $natures;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Genre")
     * @ORM\JoinTable(name="agent_genre",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_agent", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_genre", referencedColumnName="id")
     *   }
     * )
     */
    private $genres;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ActiviteAgent")
     * @ORM\JoinTable(name="agent_activite",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_agent", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_activite", referencedColumnName="id")
     *   }
     * )
     */
    private $activites;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Agentivite")
     * @ORM\JoinTable(name="agent_agentivite",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_agent", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_agentivite", referencedColumnName="id")
     *   }
     * )
     */
    private $agentivites;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->statutAffiches = new \Doctrine\Common\Collections\ArrayCollection();
        $this->natures        = new \Doctrine\Common\Collections\ArrayCollection();
        $this->genres         = new \Doctrine\Common\Collections\ArrayCollection();
        $this->activites      = new \Doctrine\Common\Collections\ArrayCollection();
        $this->agentivites    = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getDesignation(): ?string
    {
        return $this->sanitizeWysiwygString($this->sanitizeOpenXMLString($this->designation));
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $this->sanitizeWysiwygString($this->sanitizeOpenXMLString($designation));
        return $this;
    }


    public function getAttestation(): ?Attestation
    {
        return $this->attestation;
    }

    public function setAttestation(?Attestation $attestation): self
    {
        $this->attestation = $attestation;
        return $this;
    }

    /**
     * @return Collection|StatutAffiche[]
     */
    public function getStatutAffiches(): Collection
    {
        return $this->statutAffiches;
    }

    public function addStatutAffich(StatutAffiche $statutAffich): self
    {
        if (!$this->statutAffiches->contains($statutAffich)) {
            $this->statutAffiches[] = $statutAffich;
        }
        return $this;
    }

    public function removeStatutAffich(StatutAffiche $statutAffich): self
    {
        if ($this->statutAffiches->contains($statutAffich)) {
            $this->statutAffiches->removeElement($statutAffich);
        }
        return $this;
    }

    /**
     * @return Collection|Nature[]
     */
    public function getNatures(): Collection
    {
        return $this->natures;
    }

    public function addNature(Nature $nature): self
    {
        if (!$this->natures->contains($nature)) {
            $this->natures[] = $nature;
        }
        return $this;
    }

    public function removeNature(Nature $nature): self
    {
        if ($this->natures->contains($nature)) {
            $this->natures->removeElement($nature);
        }
        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): self
    {
        if (!$this->genres->contains($genre)) {
            $this->genres[] = $genre;
        }
        return $this;
    }

    public function removeGenre(Genre $genre): self
    {
        if ($this->genres->contains($genre)) {
            $this->genres->removeElement($genre);
        }
        return $this;
    }

    /**
     * @return Collection|ActiviteAgent[]
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(ActiviteAgent $activite): self
    {
        if (!$this->activites->contains($activite)) {
            $this->activites[] = $activite;
        }
        return $this;
    }

    public function removeActivite(ActiviteAgent $activite): self
    {
        if ($this->activites->contains($activite)) {
            $this->activites->removeElement($activite);
        }
        return $this;
    }

    /**
     * @return Collection|Agentivite[]
     */
    public function getAgentivites(): Collection
    {
        return $this->agentivites;
    }

    public function addAgentivite(Agentivite $agentivite): self
    {
        if (!$this->agentivites->contains($agentivite)) {
            $this->agentivites[] = $agentivite;
        }
        return $this;
    }

    public function removeAgentivite(Agentivite $agentivite): self
    {
        if ($this->agentivites->contains($agentivite)) {
            $this->agentivites->removeElement($agentivite);
        }
        return $this;
    }

    public function isBlank() :bool
    {
        return !(
               strlen($this->designation) > 0
            || !$this->agentivites->isEmpty()
            || !$this->statutAffiches->isEmpty()
            || !$this->natures->isEmpty()
            || !$this->genres->isEmpty()
            || !$this->activites->isEmpty()
            || !is_null($this->localisation)
            || strlen($this->commentaireFr) > 0
            || strlen($this->commentaireEn) > 0
        );
    }
}
