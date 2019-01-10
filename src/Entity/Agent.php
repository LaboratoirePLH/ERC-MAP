<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Agent
 *
 * @ORM\Table(name="agent", indexes={@ORM\Index(name="IDX_268B9C9DA1342FAE", columns={"id_attest"}), @ORM\Index(name="IDX_268B9C9D16F64486", columns={"id_loc"})})
 * @ORM\Entity
 */
class Agent
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="agent_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="designation", type="text", nullable=true)
     */
    private $designation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_agent", type="text", nullable=true)
     */
    private $comAgent;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_agent_en", type="text", nullable=true)
     */
    private $comAgentEn;

    /**
     * @var \Attestation
     *
     * @ORM\ManyToOne(targetEntity="Attestation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_attest", referencedColumnName="id")
     * })
     */
    private $idAttest;

    /**
     * @var \Localisation
     *
     * @ORM\ManyToOne(targetEntity="Localisation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_loc", referencedColumnName="id")
     * })
     */
    private $idLoc;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="StatuAff", mappedBy="idAgent")
     */
    private $idStatut;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Nature", mappedBy="idAgent")
     */
    private $idNat;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Genre", mappedBy="idAgent")
     */
    private $idGenre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="ActiviteAgent", mappedBy="idAgent")
     */
    private $idActivite;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Agentivite", mappedBy="idAgent")
     */
    private $idAgentivite;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idStatut = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idNat = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idGenre = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idActivite = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idAgentivite = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(?string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getComAgent(): ?string
    {
        return $this->comAgent;
    }

    public function setComAgent(?string $comAgent): self
    {
        $this->comAgent = $comAgent;

        return $this;
    }

    public function getComAgentEn(): ?string
    {
        return $this->comAgentEn;
    }

    public function setComAgentEn(?string $comAgentEn): self
    {
        $this->comAgentEn = $comAgentEn;

        return $this;
    }

    public function getIdAttest(): ?Attestation
    {
        return $this->idAttest;
    }

    public function setIdAttest(?Attestation $idAttest): self
    {
        $this->idAttest = $idAttest;

        return $this;
    }

    public function getIdLoc(): ?Localisation
    {
        return $this->idLoc;
    }

    public function setIdLoc(?Localisation $idLoc): self
    {
        $this->idLoc = $idLoc;

        return $this;
    }

    /**
     * @return Collection|StatuAff[]
     */
    public function getIdStatut(): Collection
    {
        return $this->idStatut;
    }

    public function addIdStatut(StatuAff $idStatut): self
    {
        if (!$this->idStatut->contains($idStatut)) {
            $this->idStatut[] = $idStatut;
            $idStatut->addIdAgent($this);
        }

        return $this;
    }

    public function removeIdStatut(StatuAff $idStatut): self
    {
        if ($this->idStatut->contains($idStatut)) {
            $this->idStatut->removeElement($idStatut);
            $idStatut->removeIdAgent($this);
        }

        return $this;
    }

    /**
     * @return Collection|Nature[]
     */
    public function getIdNat(): Collection
    {
        return $this->idNat;
    }

    public function addIdNat(Nature $idNat): self
    {
        if (!$this->idNat->contains($idNat)) {
            $this->idNat[] = $idNat;
            $idNat->addIdAgent($this);
        }

        return $this;
    }

    public function removeIdNat(Nature $idNat): self
    {
        if ($this->idNat->contains($idNat)) {
            $this->idNat->removeElement($idNat);
            $idNat->removeIdAgent($this);
        }

        return $this;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getIdGenre(): Collection
    {
        return $this->idGenre;
    }

    public function addIdGenre(Genre $idGenre): self
    {
        if (!$this->idGenre->contains($idGenre)) {
            $this->idGenre[] = $idGenre;
            $idGenre->addIdAgent($this);
        }

        return $this;
    }

    public function removeIdGenre(Genre $idGenre): self
    {
        if ($this->idGenre->contains($idGenre)) {
            $this->idGenre->removeElement($idGenre);
            $idGenre->removeIdAgent($this);
        }

        return $this;
    }

    /**
     * @return Collection|ActiviteAgent[]
     */
    public function getIdActivite(): Collection
    {
        return $this->idActivite;
    }

    public function addIdActivite(ActiviteAgent $idActivite): self
    {
        if (!$this->idActivite->contains($idActivite)) {
            $this->idActivite[] = $idActivite;
            $idActivite->addIdAgent($this);
        }

        return $this;
    }

    public function removeIdActivite(ActiviteAgent $idActivite): self
    {
        if ($this->idActivite->contains($idActivite)) {
            $this->idActivite->removeElement($idActivite);
            $idActivite->removeIdAgent($this);
        }

        return $this;
    }

    /**
     * @return Collection|Agentivite[]
     */
    public function getIdAgentivite(): Collection
    {
        return $this->idAgentivite;
    }

    public function addIdAgentivite(Agentivite $idAgentivite): self
    {
        if (!$this->idAgentivite->contains($idAgentivite)) {
            $this->idAgentivite[] = $idAgentivite;
            $idAgentivite->addIdAgent($this);
        }

        return $this;
    }

    public function removeIdAgentivite(Agentivite $idAgentivite): self
    {
        if ($this->idAgentivite->contains($idAgentivite)) {
            $this->idAgentivite->removeElement($idAgentivite);
            $idAgentivite->removeIdAgent($this);
        }

        return $this;
    }

}
