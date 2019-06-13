<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieMateriau
 *
 * @ORM\Table(name="categorie_materiau")
 * @ORM\Entity
 */
class CategorieMateriau extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
