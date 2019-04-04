<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Attestation
 *
 * @ORM\Table(name="attestation")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Attestation
{
    use Traits\Dated;
    use Traits\EntityId;
    use Traits\Located;
    use Traits\Tracked;
    use Traits\Translatable;
    use Traits\TranslatedComment;

    /**
     * @var \Source
     *
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="attestations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_source", referencedColumnName="id", nullable=false)
     * })
     */
    private $source;

    /**
     * @var string|null
     *
     * @ORM\Column(name="passage", type="string", length=255, nullable=true)
     */
    private $passage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="extrait_sans_restitution", type="text", nullable=true)
     */
    private $extraitSansRestitution;

    /**
     * @var string|null
     *
     * @ORM\Column(name="extrait_avec_restitution", type="text", nullable=true)
     */
    private $extraitAvecRestitution;

    /**
     * @var string|null
     *
     * @ORM\Column(name="translitteration", type="text", nullable=true)
     */
    private $translitteration;

    /**
     * @var int|null
     *
     * @ORM\Column(name="fiabilite_attestation", type="smallint", nullable=true)
     */
    private $fiabiliteAttestation;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="prose", type="boolean", nullable=true, options={"default"="1"})
     */
    private $prose = true;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="poesie", type="boolean", nullable=true, options={"default"="0"})
     */
    private $poesie = false;

    /**
     * @var \EtatFiche
     *
     * @ORM\ManyToOne(targetEntity="EtatFiche")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_etat_fiche", referencedColumnName="id")
     * })
     */
    private $etatFiche;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Pratique")
     * @ORM\JoinTable(name="attestation_pratique",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_attestation", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_pratique", referencedColumnName="id")
     *   }
     * )
     */
    private $pratiques;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="CategorieOccasion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie_occasion", referencedColumnName="id")
     * })
     */
    private $categorieOccasion;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToOne(targetEntity="Occasion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_occasion", referencedColumnName="id")
     * })
     */
    private $occasion;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AttestationMateriel", mappedBy="attestation", orphanRemoval=true)
     */
    private $attestationMateriels;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Agent", mappedBy="attestation", orphanRemoval=true, cascade={"persist"})
     */
    private $agents;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Formule", mappedBy="attestation", orphanRemoval=true, cascade={"persist"})
     */
    private $formules;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ContientElement", mappedBy="attestation")
     * @ORM\OrderBy({"positionElement" = "ASC"})
     * @Assert\Valid
     */
    private $contientElements;

    public function __construct()
    {
        $this->pratiques = new ArrayCollection();
        $this->attestationMateriels = new ArrayCollection();
        $this->agents = new ArrayCollection();
        $this->formules = new ArrayCollection();
        $this->contientElements = new ArrayCollection();
    }

    // Hack for attestationSource form
    public function setId(int $id): self
    {
        return $this;
    }

    public function getPassage(): ?string
    {
        return $this->passage;
    }

    public function setPassage(?string $passage): self
    {
        $this->passage = $passage;
        return $this;
    }

    public function getExtraitSansRestitution(): ?string
    {
        return $this->extraitSansRestitution;
    }

    public function setExtraitSansRestitution(?string $extraitSansRestitution): self
    {
        $this->extraitSansRestitution = $extraitSansRestitution;
        return $this;
    }

    public function getExtraitAvecRestitution(): ?string
    {
        return $this->extraitAvecRestitution;
    }

    public function setExtraitAvecRestitution(?string $extraitAvecRestitution): self
    {
        $this->extraitAvecRestitution = $extraitAvecRestitution;
        return $this;
    }

    public function getTranslitteration(): ?string
    {
        return $this->translitteration;
    }

    public function setTranslitteration(?string $translitteration): self
    {
        $this->translitteration = $translitteration;
        return $this;
    }

    public function getFiabiliteAttestation(): ?int
    {
        return $this->fiabiliteAttestation;
    }

    public function setFiabiliteAttestation(?int $fiabiliteAttestation): self
    {
        $this->fiabiliteAttestation = $fiabiliteAttestation;
        return $this;
    }

    public function getProse(): ?bool
    {
        return $this->prose;
    }

    public function setProse(?bool $prose): self
    {
        $this->prose = $prose;
        return $this;
    }

    public function getPoesie(): ?bool
    {
        return $this->poesie;
    }

    public function setPoesie(?bool $poesie): self
    {
        $this->poesie = $poesie;
        return $this;
    }

    public function getSource(): ?Source
    {
        return $this->source;
    }

    public function setSource(?Source $source): self
    {
        $this->source = $source;
        return $this;
    }

    public function getEtatFiche(): ?EtatFiche
    {
        return $this->etatFiche;
    }

    public function setEtatFiche(?EtatFiche $etatFiche): self
    {
        $this->etatFiche = $etatFiche;
        return $this;
    }

    /**
     * @return Collection|Pratique[]
     */
    public function getPratiques(): Collection
    {
        return $this->pratiques;
    }

    public function addPratique(Pratique $pratique): self
    {
        if (!$this->pratiques->contains($pratique)) {
            $this->pratiques[] = $pratique;
        }
        return $this;
    }

    public function removePratique(Pratique $pratique): self
    {
        if ($this->pratiques->contains($pratique)) {
            $this->pratiques->removeElement($pratique);
        }
        return $this;
    }

    public function getCategorieOccasion(): ?CategorieOccasion
    {
        return $this->categorieOccasion;
    }

    public function setCategorieOccasion(?CategorieOccasion $categorieOccasion): self
    {
        $this->categorieOccasion = $categorieOccasion;
        return $this;
    }

    public function getOccasion(): ?Occasion
    {
        return $this->occasion;
    }

    public function setOccasion(?Occasion $occasion): self
    {
        $this->occasion = $occasion;
        return $this;
    }

    public function setTypeCategorieOccasion($data): self
    {
        $this->setCategorieOccasion($data['categorieOccasion']);
        $this->setOccasion($data['occasion']);
        return $this;
    }

    public function getTypeCategorieOccasion(): array
    {
        return [
            'occasion' => $this->getOccasion(),
            'categorieOccasion' => $this->getCategorieOccasion(),
        ];
    }

    /**
     * @return Collection|AttestationMateriel[]
     */
    public function getAttestationMateriels(): Collection
    {
        return $this->attestationMateriels;
    }

    public function addAttestationMateriel(AttestationMateriel $attestationMateriel): self
    {
        if (!$this->attestationMateriels->contains($attestationMateriel)) {
            $this->attestationMateriels[] = $attestationMateriel;
            $attestationMateriel->setAttestation($this);
        }
        return $this;
    }

    public function removeAttestationMateriel(AttestationMateriel $attestationMateriel): self
    {
        if ($this->attestationMateriels->contains($attestationMateriel)) {
            $this->attestationMateriels->removeElement($attestationMateriel);
        }
        return $this;
    }

    /**
     * @return Collection|Agent[]
     */
    public function getAgents(): Collection
    {
        return $this->agents;
    }

    public function addAgent(Agent $agent): self
    {
        if (!$this->agents->contains($agent)) {
            $this->agents[] = $agent;
            $agent->setAttestation($this);
        }
        return $this;
    }

    public function removeAgent(Agent $agent): self
    {
        if ($this->agents->contains($agent)) {
            $this->agents->removeElement($agent);
        }
        return $this;
    }

    /**
     * @return Collection|Formule[]
     */
    public function getFormules(): Collection
    {
        return $this->formules;
    }

    public function addFormule(Formule $formule): self
    {
        if (!$this->formules->contains($formule)) {
            $this->formules[] = $formule;
            $formule->setAttestation($this);
        }
        return $this;
    }

    public function removeFormule(Formule $formule): self
    {
        if ($this->formules->contains($formule)) {
            $this->formules->removeElement($formule);
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
