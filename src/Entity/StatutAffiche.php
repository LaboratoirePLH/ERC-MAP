<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatutAffiche
 *
 * @ORM\Table(name="statut_affiche")
 * @ORM\Entity
 */
class StatutAffiche
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
