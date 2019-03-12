<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NombreElement
 *
 * @ORM\Table(name="nombre_element")
 * @ORM\Entity
 */
class NombreElement
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
