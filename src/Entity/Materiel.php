<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Materiel
 *
 * @ORM\Table(name="materiel")
 * @ORM\Entity
 */
class Materiel
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var \CategorieMateriel
     *
     * @ORM\ManyToOne(targetEntity="CategorieMateriel", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_materiel_id", referencedColumnName="id")
     * })
     */
    private $categorieMateriel;


    public function getCategorieMateriel(): ?CategorieMateriel
    {
        return $this->categorieMateriel;
    }

    public function setCategorieMateriel(?CategorieMateriel $categorieMateriel): self
    {
        $this->categorieMateriel = $categorieMateriel;
        return $this;
    }
}
