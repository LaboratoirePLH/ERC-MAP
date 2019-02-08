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
