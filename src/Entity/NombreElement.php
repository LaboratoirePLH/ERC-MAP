<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NombreElement
 *
 * @ORM\Table(name="nombre_element")
 * @ORM\Entity
 */
class NombreElement extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
