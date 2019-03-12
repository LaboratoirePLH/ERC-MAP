<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * QFonction
 *
 * @ORM\Table(name="q_fonction")
 * @ORM\Entity
 */
class QFonction
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
