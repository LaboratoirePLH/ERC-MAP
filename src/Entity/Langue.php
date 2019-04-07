<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Langue
 *
 * @ORM\Table(name="langue")
 * @ORM\Entity
 */
class Langue extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
