<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EtatFiche
 *
 * @ORM\Table(name="etat_fiche")
 * @ORM\Entity
 */
class EtatFiche extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="open_access", type="boolean", nullable=false, options={"default": false})
     */
    private $openAccess = false;

    public function getOpenAccess(): bool
    {
        return $this->openAccess;
    }

    public function setOpenAccess(bool $openAccess): self
    {
        $this->openAccess = $openAccess;
        return $this;
    }
}
