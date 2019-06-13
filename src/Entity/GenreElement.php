<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GenreElement
 *
 * @ORM\Table(name="genre_element")
 * @ORM\Entity
 */
class GenreElement extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
