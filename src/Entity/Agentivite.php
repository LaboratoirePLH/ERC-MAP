<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agentivite
 *
 * @ORM\Table(name="agentivite")
 * @ORM\Entity
 */
class Agentivite
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

    public function getId(): ?int
    {
        return $this->id;
    }
}
