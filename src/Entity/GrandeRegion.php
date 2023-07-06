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
     * @var geometry|null
     *
     * @ORM\Column(name="geom", type="geometry", nullable=true, options={"geometry_type"="MULTIPOLYGON", "srid"=4326})
     */
    private $geom;

    /**
     * @var int
     *
     * @ORM\Column(name="progression", type="smallint", nullable=false, options={"unsigned": true, "default":0})
     */
    private $progression = 0;

    public function getGeom()
    {
        return $this->geom;
    }

    public function setGeom($geom): self
    {
        $this->geom = $geom;

        return $this;
    }

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
