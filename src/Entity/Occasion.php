<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Occasion
 *
 * @ORM\Table(name="occasion")
 * @ORM\Entity
 */
class Occasion extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var CategorieOccasion
     *
     * @ORM\ManyToOne(targetEntity="CategorieOccasion", fetch="EAGER")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_occasion_id", referencedColumnName="id")
     * })
     */
    private $categorieOccasion;


    public function getCategorieOccasion(): ?CategorieOccasion
    {
        return $this->categorieOccasion;
    }

    public function setCategorieOccasion(?CategorieOccasion $categorieOccasion): self
    {
        $this->categorieOccasion = $categorieOccasion;
        return $this;
    }

    public function toArray(): array
    {
        return array_merge(['id' => $this->id], $this->getTranslatedName());
    }
}
