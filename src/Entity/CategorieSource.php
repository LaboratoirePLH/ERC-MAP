<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieSource
 *
 * @ORM\Table(name="categorie_source")
 * @ORM\Entity
 */
class CategorieSource
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
}
