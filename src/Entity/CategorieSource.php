<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieSource
 *
 * @ORM\Table(name="categorie_source")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class CategorieSource extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\Cached;
    use Traits\TranslatedName;

    public function toArray(): array
    {
        return array_merge(['id' => $this->id], $this->getTranslatedName());
    }
}
