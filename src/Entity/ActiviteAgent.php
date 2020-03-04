<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ActiviteAgent
 *
 * @ORM\Table(name="activite_agent")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class ActiviteAgent extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\Cached;
    use Traits\TranslatedName;

    public function toArray(): array
    {
        return array_merge(['id' => $this->id], $this->getTranslatedName());
    }
}
