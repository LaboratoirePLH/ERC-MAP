<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pratique
 *
 * @ORM\Table(name="pratique")
 * @ORM\Entity
 */
class Pratique
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
