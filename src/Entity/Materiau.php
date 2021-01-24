<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Materiau
 *
 * @ORM\Table(name="materiau")
 * @ORM\Entity
 */
class Materiau extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var CategorieMateriau
     *
     * @ORM\ManyToOne(targetEntity="CategorieMateriau", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_materiau_id", referencedColumnName="id")
     * })
     */
    private $categorieMateriau;

    public function getCategorieMateriau(): ?CategorieMateriau
    {
        return $this->categorieMateriau;
    }

    public function setCategorieMateriau(?CategorieMateriau $categorieMateriau): self
    {
        $this->categorieMateriau = $categorieMateriau;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(['id' => $this->id], $this->getTranslatedName());
    }
}
