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
    use Traits\TranslatedName;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

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
