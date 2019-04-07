<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NatureElement
 *
 * @ORM\Table(name="nature_element")
 * @ORM\Entity
 */
class NatureElement extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
