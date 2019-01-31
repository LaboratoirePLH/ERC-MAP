<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenreElement
 *
 * @ORM\Table(name="genre_elt")
 * @ORM\Entity
 */
class GenreElement
{
    use Traits\TranslatedName;

    /**
     * @var int
     *
     * @ORM\Column(name="id_genre", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="genre_elt_id_genre_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
