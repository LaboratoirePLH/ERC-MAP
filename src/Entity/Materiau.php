<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Materiau
 *
 * @ORM\Table(name="materiau")
 * @ORM\Entity
 */
class Materiau
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
     * @var \CategorieMateriau
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


}
