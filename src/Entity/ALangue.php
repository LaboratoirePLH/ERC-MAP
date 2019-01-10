<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ALangue
 *
 * @ORM\Table(name="a_langue")
 * @ORM\Entity
 */
class ALangue
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_langue", type="smallint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idLangue;

    /**
     * @var int
     *
     * @ORM\Column(name="id_source", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $idSource;

    public function getIdLangue(): ?int
    {
        return $this->idLangue;
    }

    public function getIdSource(): ?int
    {
        return $this->idSource;
    }


}
