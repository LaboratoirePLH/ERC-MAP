<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NombreElement
 *
 * @ORM\Table(name="nombre_elt")
 * @ORM\Entity
 */
class NombreElement
{
    use Traits\TranslatedName;

    /**
     * @var int
     *
     * @ORM\Column(name="id_nombre", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="nombre_elt_id_nombre_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
