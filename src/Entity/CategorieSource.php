<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieSource
 *
 * @ORM\Table(name="categorie_source")
 * @ORM\Entity
 */
class CategorieSource extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
