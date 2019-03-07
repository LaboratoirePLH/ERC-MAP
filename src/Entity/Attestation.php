<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Attestation
 *
 * @ORM\Table(name="attestation")
 * @ORM\Entity
 */
class Attestation
{
    use Traits\Dated;
    use Traits\Located;
    use Traits\Tracked;
    use Traits\Translatable;
    use Traits\TranslatedComment;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     */
    private $id;

    /**
     * @var \Source
     *
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="attestations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_source", referencedColumnName="id")
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
     * @ORM\ManyToMany(targetEntity="CategorieOccasion")
     * @ORM\JoinTable(name="attestation_categorie_occasion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_attestation", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_categorie_occasion", referencedColumnName="id")
     *   }
     * )
     */
    private $categorieOccasions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Occasion")
     * @ORM\JoinTable(name="attestation_occasion",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_attestation", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_occasion", referencedColumnName="id")
     *   }
     * )
     */
    private $occasions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="AttestationMateriel", mappedBy="attestation", orphanRemoval=true, cascade={"persist"})
     */
    private $attestationMateriels;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Agent", mappedBy="attestation", orphanRemoval=true, cascade={"persist"})
     */
    private $agents;

    public function __construct()
    {
        $this->pratiques = new ArrayCollection();
        $this->categorieOccasions = new ArrayCollection();
        $this->occasions = new ArrayCollection();
        $this->attestationMateriels = new ArrayCollection();
        $this->agents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|CategorieOccasion[]
     */
    public function getCategorieOccasions(): Collection
    {
        return $this->categorieOccasions;
    }

    public function addCategorieOccasion(CategorieOccasion $categorieOccasion): self
    {
        if (!$this->categorieOccasions->contains($categorieOccasion)) {
            $this->categorieOccasions[] = $categorieOccasion;
        }

        return $this;
    }

    public function removeCategorieOccasion(CategorieOccasion $categorieOccasion): self
    {
        if ($this->categorieOccasions->contains($categorieOccasion)) {
            $this->categorieOccasions->removeElement($categorieOccasion);
        }

        return $this;
    }

    /**
     * @return Collection|Occasion[]
     */
    public function getOccasions(): Collection
    {
        return $this->occasions;
    }

    public function addOccasion(Occasion $occasion): self
    {
        if (!$this->occasions->contains($occasion)) {
            $this->occasions[] = $occasion;
        }

        return $this;
    }

    public function removeOccasion(Occasion $occasion): self
    {
        if ($this->occasions->contains($occasion)) {
            $this->occasions->removeElement($occasion);
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


}
