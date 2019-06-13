<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pratique
 *
 * @ORM\Table(name="pratique")
 * @ORM\Entity
 */
class Pratique extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
