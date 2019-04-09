<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieOccasion
 *
 * @ORM\Table(name="categorie_occasion")
 * @ORM\Entity
 */
class CategorieOccasion extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
