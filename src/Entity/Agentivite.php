<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agentivite
 *
 * @ORM\Table(name="agentivite")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Agentivite extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\Cached;
    use Traits\TranslatedName;

    public function toArray(): array
    {
        return array_merge(['id' => $this->id], $this->getTranslatedName());
    }
}
