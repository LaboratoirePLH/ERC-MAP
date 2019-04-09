<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieMateriel
 *
 * @ORM\Table(name="categorie_materiel")
 * @ORM\Entity
 */
class CategorieMateriel extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
