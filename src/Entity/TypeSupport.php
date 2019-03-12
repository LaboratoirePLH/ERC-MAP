<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeSupport
 *
 * @ORM\Table(name="type_support")
 * @ORM\Entity
 */
class TypeSupport
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var \CategorieSupport
     *
     * @ORM\ManyToOne(targetEntity="CategorieSupport", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_support_id", referencedColumnName="id")
     * })
     */
    private $categorieSupport;

    public function getCategorieSupport(): ?CategorieSupport
    {
        return $this->categorieSupport;
    }

    public function setCategorieSupport(?CategorieSupport $categorieSupport): self
    {
        $this->categorieSupport = $categorieSupport;
        return $this;
    }
}
