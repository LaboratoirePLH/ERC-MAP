<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EtatFiche
 *
 * @ORM\Table(name="etat_fiche")
 * @ORM\Entity
 */
class EtatFiche extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
