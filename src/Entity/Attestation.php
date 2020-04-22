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
class Attestation extends AbstractEntity
{
    use Traits\Dated;
    use Traits\EntityId;
    use Traits\Indexed;
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
     */
    private $affichage;

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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="TraductionAttestation", mappedBy="attestation", cascade={"persist", "remove"})
     */
    private $traductions;

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
     * @ORM\OneToMany(targetEntity="AttestationOccasion", mappedBy="attestation", orphanRemoval=true)
     */
    private $attestationOccasions;

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
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $agents;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Formule", mappedBy="attestation", orphanRemoval=true, cascade={"persist"})
     * @ORM\OrderBy({"positionFormule" = "ASC"})
     */
    private $formules;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ContientElement", mappedBy="attestation")
     * @ORM\OrderBy({"positionElement" = "ASC"})
     * @Assert\Valid
     * @Assert\Expression(
     *      "this.validateElements()",
     *      message="unique_elements"
     * )
     */
    private $contientElements;

    /**
     * @ORM\ManyToMany(targetEntity="Attestation")
     * @ORM\JoinTable(name="lien_attestation",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_attestation1", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_attestation2", referencedColumnName="id")
     *   }
     * )
     */
    private $attestationsLiees;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\VerrouEntite", inversedBy="attestations", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="verrou_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * })
     */
    private $verrou;

    public function __construct()
    {
        $this->pratiques = new ArrayCollection();
        $this->attestationMateriels = new ArrayCollection();
        $this->attestationOccasions = new ArrayCollection();
        $this->agents = new ArrayCollection();
        $this->formules = new ArrayCollection();
        $this->contientElements = new ArrayCollection();
        $this->traductions = new ArrayCollection();
        $this->attestationsLiees = new ArrayCollection();
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

            // Reset passage field
            $this->passage = "";

            // Clone datation and localization
            if ($this->datation !== null) {
                $this->datation = clone $this->datation;
            }
            if ($this->localisation !== null) {
                $this->localisation = clone $this->localisation;
            }

            // Clone agents
            $cloneAgents = new ArrayCollection();
            foreach ($this->agents as $a) {
                $cloneA = clone $a;
                $cloneA->setAttestation($this);
                $cloneAgents->add($cloneA);
            }
            $this->agents = $cloneAgents;

            // Clone Occasions
            $cloneOccasions = new ArrayCollection();
            foreach ($this->attestationOccasions as $ao) {
                $cloneAo = clone $ao;
                $cloneAo->setAttestation($this);
                $cloneOccasions->add($cloneAo);
            }
            $this->attestationOccasions = $cloneOccasions;

            // Clone Matériels
            $cloneMateriels = new ArrayCollection();
            foreach ($this->attestationMateriels as $am) {
                $cloneAm = clone $am;
                $cloneAm->setAttestation($this);
                $cloneMateriels->add($cloneAm);
            }
            $this->attestationMateriels = $cloneMateriels;

            // Clone Traductions
            $cloneTraductions = new ArrayCollection();
            foreach ($this->traductions as $t) {
                $cloneT = clone $t;
                $cloneT->setAttestation($this);
                $cloneTraductions->add($cloneT);
            }
            $this->traductions = $cloneTraductions;

            // Clone contextual elements
            $cloneElements = new ArrayCollection();
            foreach ($this->contientElements as $ce) {
                $cloneCe = clone $ce;
                $cloneCe->setAttestation($this);
                $cloneElements->add($cloneCe);
            }
            $this->contientElements = $cloneElements;

            // Do not clone Attestations Liées or Formules
            $this->attestationsLiees = new ArrayCollection();
            $this->formules          = new ArrayCollection();
        }
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

    public function getAffichage(): string
    {
        $source = $this->getSource();
        $editionPrincipale = $source->getEditionPrincipaleBiblio();
        return sprintf(
            '#%d : %s %s, %s',
            $this->getId(),
            $editionPrincipale->getBiblio()->getTitreAbrege(),
            $editionPrincipale->getReferenceSource(),
            $this->getPassage()
        );
    }

    public function getExtraitAvecRestitution(): ?string
    {
        return $this->sanitizeWysiwygString($this->sanitizeOpenXMLString($this->extraitAvecRestitution));
    }

    public function setExtraitAvecRestitution(?string $extraitAvecRestitution): self
    {
        $this->extraitAvecRestitution = $this->sanitizeWysiwygString($this->sanitizeOpenXMLString($extraitAvecRestitution));
        return $this;
    }

    public function getTranslitteration(): ?string
    {
        return $this->sanitizeWysiwygString($this->sanitizeOpenXMLString($this->translitteration));
    }

    public function setTranslitteration(?string $translitteration): self
    {
        $this->translitteration = $this->sanitizeWysiwygString($this->sanitizeOpenXMLString($translitteration));
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
     * @return Collection|AttestationOccasion[]
     */
    public function getAttestationOccasions(): Collection
    {
        return $this->attestationOccasions;
    }

    public function addAttestationOccasion(AttestationOccasion $attestationOccasion): self
    {
        if (!$this->attestationOccasions->contains($attestationOccasion)) {
            $this->attestationOccasions[] = $attestationOccasion;
            $attestationOccasion->setAttestation($this);
        }
        return $this;
    }

    public function removeAttestationOccasion(AttestationOccasion $attestationOccasion): self
    {
        if ($this->attestationOccasions->contains($attestationOccasion)) {
            $this->attestationOccasions->removeElement($attestationOccasion);
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
            $contientElement->setAttestation($this);
        }
        return $this;
    }

    public function removeContientElement(ContientElement $contientElement): self
    {
        if ($this->contientElements->contains($contientElement)) {
            $this->contientElements->removeElement($contientElement);
            // set the owning side to null (unless already changed)
            if ($contientElement->getAttestation() === $this) {
                $contientElement->setAttestation(null);
            }
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

    public function validateElements(): bool
    {
        $positions = [];
        foreach ($this->getContientElements() as $ce) {
            if (in_array($ce->getPositionElement(), $positions)) {
                return false;
            } else {
                $positions[] = $ce->getPositionElement();
            }
        }
        return true;
    }

    public function elementsFormule(): array
    {
        $elements = [];
        foreach ($this->getContientElements() as $ce) {
            $el = $ce->getElement();
            $elements[$el->getId()] = $el->getEtatAbsolu();
        }
        return $elements;
    }

    /**
     * @return Collection|TraductionAttestation[]
     */
    public function getTraductions(): Collection
    {
        return $this->traductions;
    }

    public function addTraduction(TraductionAttestation $traduction): self
    {
        if (!$this->traductions->contains($traduction)) {
            $this->traductions[] = $traduction;
            $traduction->setAttestation($this);
        }

        return $this;
    }

    public function removeTraduction(TraductionAttestation $traduction): self
    {
        if ($this->traductions->contains($traduction)) {
            $this->traductions->removeElement($traduction);
            // set the owning side to null (unless already changed)
            if ($traduction->getAttestation() === $this) {
                $traduction->setAttestation(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        $toArray = function ($entry) {
            return $entry->toArray();
        };

        $formule1 = $this->formules->filter(function ($f) {
            return $f->getPositionFormule() === 1;
        })->first();

        return [
            'source'                 => $this->source->getId(),
            'prose'                  => $this->prose,
            'poesie'                 => $this->poesie,
            'passage'                => $this->passage,
            'extraitAvecRestitution' => $this->extraitAvecRestitution,
            'translitteration'       => $this->translitteration,
            'fiabilite'              => $this->fiabiliteAttestation,
            'traductions'            => $this->traductions->map($toArray)->getValues(),
            'pratiques'              => $this->pratiques->map($toArray)->getValues(),
            'materiels'              => $this->attestationMateriels->map(function ($am) {
                return [
                    'categorieMateriel' => $am->getCategorieMateriel() === null ? null : $am->getCategorieMateriel()->toArray(),
                    'materiel'          => $am->getMateriel() === null ? null : $am->getMateriel()->toArray(),
                    'quantite'          => $am->getQuantite()
                ];
            })->getValues(),
            'occasions' => $this->attestationOccasions->map(function ($ao) {
                return [
                    'categorieOccasion' => $ao->getCategorieOccasion() === null ? null : $ao->getCategorieOccasion()->toArray(),
                    'occasion'          => $ao->getOccasion() === null ? null : $ao->getOccasion()->toArray()
                ];
            })->getValues(),
            'agents'        => $this->agents->map($toArray)->getValues(),
            'datation'      => $this->datation === null ? null : $this->datation->toArray(),
            'localisation'  => $this->localisation === null ? null : $this->localisation->toArray(),
            'elements'      => $this->contientElements->map(function ($e) {
                return $e->toArray();
            })->getValues(),
            'elementIds'    => $this->contientElements->map(function ($e) {
                return $e->getElement()->getId();
            })->getValues(),
            'formule1'      => $formule1 instanceof Formule ? $formule1->toArray() : null,
            'commentaireFr' => $this->commentaireFr,
            'commentaireEn' => $this->commentaireEn
        ];
    }

    /**
     * @return Collection|Attestation[]
     */
    public function getAttestationsLiees(): Collection
    {
        return $this->attestationsLiees;
    }

    public function addAttestationsLiee(Attestation $attestationLiee): self
    {
        if (!$this->attestationsLiees->contains($attestationLiee)) {
            $this->attestationsLiees[] = $attestationLiee;
            $attestationLiee->addAttestationsLiee($this);
        }

        return $this;
    }

    public function removeAttestationsLiee(Attestation $attestationLiee): self
    {
        if ($this->attestationsLiees->contains($attestationLiee)) {
            $this->attestationsLiees->removeElement($attestationLiee);
            $attestationLiee->removeAttestationsLiee($this);
        }

        return $this;
    }
}
