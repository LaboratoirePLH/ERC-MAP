<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait Tracked
{
       /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=false)
     */
    private $dateCreation;

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime", nullable=false)
     */
    private $dateModification;

    public function getDateModification(): ?\DateTimeInterface
    {
        return $this->dateModification;
    }

    public function setDateModification(\DateTimeInterface $dateModification): self
    {
        $this->dateModification = $dateModification;
        return $this;
    }

    /**
     * @var \Chercheur
     *
     * @ORM\ManyToOne(targetEntity="Chercheur", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_creation_id", referencedColumnName="id", nullable=false)
     * })
     */
    private $createur;

    public function getCreateur(): ?Chercheur
    {
        return $this->createur;
    }

    public function setCreateur(?Chercheur $createur): self
    {
        $this->createur = $createur;
        return $this;
    }

    /**
     * @var \Chercheur|null
     *
     * @ORM\ManyToOne(targetEntity="Chercheur", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_edition_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $dernierEditeur;

    public function getDernierEditeur(): ?Chercheur
    {
        return $this->dernierEditeur;
    }

    public function setDernierEditeur(?Chercheur $dernierEditeur): self
    {
        $this->dernierEditeur = $dernierEditeur;
        return $this;
    }
}