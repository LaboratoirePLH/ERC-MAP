<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrandeRegion
 *
 * @ORM\Table(name="grande_region")
 * @ORM\Entity
 */
class GrandeRegion
{
    use Traits\TranslatedName;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     */
    private $id;

    /**
     * @var geometry|null
     *
     * @ORM\Column(name="geom", type="geometry", nullable=true, options={"geometry_type"="MULTIPOLYGON", "srid"=4326})
     */
    private $geom;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGeom()
    {
        return $this->geom;
    }

    public function setGeom($geom): self
    {
        $this->geom = $geom;

        return $this;
    }
}
