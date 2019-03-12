<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieSupport
 *
 * @ORM\Table(name="categorie_support")
 * @ORM\Entity
 */
class CategorieSupport
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
