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

    public function getGeom()
    {
        return $this->geom;
    }

    public function setGeom($geom): self
    {
        $this->geom = $geom;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(['id' => $this->id], $this->getTranslatedName());
    }
}
