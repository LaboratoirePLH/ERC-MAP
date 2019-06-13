<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Nature
 *
 * @ORM\Table(name="nature")
 * @ORM\Entity
 */
class Nature extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
