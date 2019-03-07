<?php

namespace App\Entity\Traits;

use App\Entity\Datation;

use Doctrine\ORM\Mapping as ORM;

trait DatedWithFiability
{
    use Dated;

    /**
     * @var int|null
     *
     * @ORM\Column(name="fiabilite_datation", type="smallint", nullable=true)
     */
    private $fiabiliteDatation;

    public function getFiabiliteDatation(): ?int
    {
        return $this->fiabiliteDatation;
    }

    public function setFiabiliteDatation(?int $fiabiliteDatation): self
    {
        $this->fiabiliteDatation = $fiabiliteDatation;
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function _updateFiabiliteDatation(){
        $fiabDatation = 5;
        if($this->getEstDatee() && !is_null($datation = $this->getDatation())){
            $delta = abs($datation->getPostQuem() - $datation->getAnteQuem());
            if($delta <= 5){ $fiabDatation = 1; }
            else if($delta <= 50){ $fiabDatation = 2; }
            else if($delta <= 100){ $fiabDatation = 3; }
            else if($delta <= 200){ $fiabDatation = 4; }
            else { $fiabDatation = 5; }
        }
        $this->setFiabiliteDatation($fiabDatation);
    }
}