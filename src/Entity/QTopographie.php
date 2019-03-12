<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * QTopographie
 *
 * @ORM\Table(name="q_topographie")
 * @ORM\Entity
 */
class QTopographie
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
