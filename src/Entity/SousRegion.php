<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SousRegion
 *
 * @ORM\Table(name="sous_region")
 * @ORM\Entity
 */
class SousRegion extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var int
     *
     * @ORM\Column(name="progression", type="smallint", nullable=false, options={"unsigned": true, "default":0})
     */
    private $progression = 0;

    /**
     * @var GrandeRegion
     *
     * @ORM\ManyToOne(targetEntity="GrandeRegion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grande_region_id", referencedColumnName="id")
     * })
     */
    private $grandeRegion;

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

    public function getGrandeRegion(): ?GrandeRegion
    {
        return $this->grandeRegion;
    }

    public function setGrandeRegion(?GrandeRegion $grandeRegion): self
    {
        $this->grandeRegion = $grandeRegion;

        return $this;
    }

    public function toArray(): array
    {
        return array_merge(['id' => $this->id], $this->getTranslatedName());
    }
}
