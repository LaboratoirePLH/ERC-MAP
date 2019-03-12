<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SousRegion
 *
 * @ORM\Table(name="sous_region")
 * @ORM\Entity
 */
class SousRegion
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var geometry|null
     *
     * @ORM\Column(name="geom", type="geometry", nullable=true, options={"geometry_type"="POINT", "srid"=4326})
     */
    private $geom;

    /**
     * @var \GrandeRegion
     *
     * @ORM\ManyToOne(targetEntity="GrandeRegion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grande_region_id", referencedColumnName="id")
     * })
     */
    private $grandeRegion;

    public function getGeom()
    {
        return $this->geom;
    }

    public function setGeom($geom): self
    {
        $this->geom = $geom;

        return $this;
    }

    public function getGrandeRegion(): ?GrandeRegion
    {
        return $this->grandeRegion;
    }

    public function setGrandeRegion(?GrandeRegion $grandeRegion): self
    {
        $this->grandeRegion = $grandeRegion;

        return $this;
    }
}
