<?php

namespace App\Entity\Traits;

use App\Entity\Chercheur;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;

trait Tracked
{
    use Created;
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
     *   @ORM\JoinColumn(name="user_edition_id", referencedColumnName="id", nullable=false)
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

    /**
     * @var int
     *
     * @ORM\Column(name="version", type="integer", nullable=false)
     */
    private $version;

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function _setupTracking($event)
    {
        $now = new \DateTime();
        $this->setDateCreation($now);
        $this->setDateModification($now);
        $this->setVersion(1);
    }

    /**
     * @ORM\PreUpdate
     */
    public function _updateTracking(PreUpdateEventArgs $event)
    {
        $changeset = $event->getEntityChangeSet();
        if (array_keys($changeset) === ["verrou"] || empty($changeset)) {
            return;
        }
        $now = new \DateTime();
        $this->setDateModification($now);
        $this->setVersion($this->getVersion() + 1);
    }
}
