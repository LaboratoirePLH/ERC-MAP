<?php

namespace App\Entity\Traits;

use App\Entity\Chercheur;

use Doctrine\ORM\Mapping as ORM;

trait Created
{
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
}