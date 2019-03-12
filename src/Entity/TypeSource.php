<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeSource
 *
 * @ORM\Table(name="type_source")
 * @ORM\Entity
 */
class TypeSource
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var \CategorieSource|null
     *
     * @ORM\ManyToOne(targetEntity="CategorieSource", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_source_id", referencedColumnName="id")
     * })
     */
    private $categorieSource;

    public function getCategorieSource(): ?CategorieSource
    {
        return $this->categorieSource;
    }

    public function setCategorieSource(?CategorieSource $categorieSource): self
    {
        $this->categorieSource = $categorieSource;
        return $this;
    }
}
