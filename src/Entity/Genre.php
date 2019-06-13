<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Genre
 *
 * @ORM\Table(name="genre")
 * @ORM\Entity
 */
class Genre extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
