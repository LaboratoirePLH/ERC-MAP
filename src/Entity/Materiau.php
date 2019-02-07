<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Materiau
 *
 * @ORM\Table(name="a_mat", indexes={@ORM\Index(name="IDX_9A9F01A47FE4B2B", columns={"id_type"})})
 * @ORM\Entity
 */
class Materiau
{
    use Traits\TranslatedName;
    /**
     * @var int
     *
     * @ORM\Column(name="id_mat", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="a_mat_id_mat_seq", allocationSize=1, initialValue=1)
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
     *   @ORM\JoinColumn(name="id_type", referencedColumnName="id")
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
