<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieElement
 *
 * @ORM\Table(name="categorie_element")
 * @ORM\Entity
 */
class CategorieElement extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
}
