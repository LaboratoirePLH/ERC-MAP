<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActiviteAgent
 *
 * @ORM\Table(name="activite_agent")
 * @ORM\Entity
 */
class ActiviteAgent
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
