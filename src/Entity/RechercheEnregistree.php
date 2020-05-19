<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formule
 *
 * @ORM\Table(name="recherche_enregistree")
 * @ORM\Entity(repositoryClass="App\Repository\RechercheEnregistreeRepository")
 */
class RechercheEnregistree extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\Created;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="mode", type="string", length=10, nullable=false)
     */
    private $mode;

    /**
     * @var string
     *
     * @ORM\Column(name="criteria", type="text", nullable=false)
     */
    private $criteria;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getCriteria(): ?string
    {
        return $this->criteria;
    }

    public function setCriteria(string $criteria): self
    {
        $this->criteria = $criteria;

        return $this;
    }
}
