<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActiviteAgent
 *
 * @ORM\Table(name="activite_agent")
 * @ORM\Entity
 */
class ActiviteAgent extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
