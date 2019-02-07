<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeSupport
 *
 * @ORM\Table(name="type_support", indexes={@ORM\Index(name="IDX_A82584503826693B", columns={"id_cat_supp"})})
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
     * @ORM\SequenceGenerator(sequenceName="type_support_id_seq", allocationSize=1, initialValue=1)
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
     *   @ORM\JoinColumn(name="id_cat_supp", referencedColumnName="id")
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
