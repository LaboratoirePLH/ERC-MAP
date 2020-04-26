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

    /**
     * @var int
     */
    private $createurId;

    public function getCreateur(): Chercheur
    {
        return $this->createur;
    }

    public function getCreateurId(): int
    {
        return $this->getCreateur()->getId();
    }

    public function setCreateur(Chercheur $createur): self
    {
        $this->createur = $createur;
        return $this;
    }
}
