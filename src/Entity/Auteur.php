<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Auteur
 *
 * @ORM\Table(name="auteur")
 * @ORM\Entity
 */
class Auteur extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Titre", mappedBy="auteurs", fetch="EXTRA_LAZY")
     */
    private $titres;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->titres = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return Collection|Titre[]
     */
    public function getTitres(): Collection
    {
        return $this->titres;
    }

    public function addTitre(Titre $titre): self
    {
        if (!$this->titres->contains($titre)) {
            $this->titres[] = $titre;
            $titre->addAuteur($this);
        }
        return $this;
    }

    public function removeTitre(Titre $titre): self
    {
        if ($this->titres->contains($titre)) {
            $this->titres->removeElement($titre);
            $titre->removeAuteur($this);
        }
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(['id' => $this->id], $this->getTranslatedName());
    }
}
