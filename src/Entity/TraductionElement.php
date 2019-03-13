<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TraductionElement
 *
 * @ORM\Table(name="traduction_element")
 * @ORM\Entity
 */
class TraductionElement
{
    use Traits\EntityId;
    use Traits\TranslatedName;

    /**
     * @var \Element
     *
     * @ORM\ManyToOne(targetEntity="Element", inversedBy="traductions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_element", referencedColumnName="id")
     * })
     */
    private $element;

    public function getElement(): ?Element
    {
        return $this->element;
    }

    public function setElement(?Element $element): self
    {
        $this->element = $element;
        return $this;
    }
}
