<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agentivite
 *
 * @ORM\Table(name="agentivite")
 * @ORM\Entity
 */
class Agentivite
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
