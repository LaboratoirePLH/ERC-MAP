<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrandeRegion
 *
 * @ORM\Table(name="grande_region")
 * @ORM\Entity
 */
class GrandeRegion extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var int
     *
     * @ORM\Column(name="progression", type="smallint", nullable=false, options={"unsigned": true, "default":0})
     */
    private $progression = 0;

    public function getProgression(): ?int
    {
        return $this->progression;
    }

    public function setProgression($progression): self
    {
        if (!is_numeric($progression)) {
            $progression = 0;
        }
        $this->progression = $progression;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(['id' => $this->id], $this->getTranslatedName());
    }
}
