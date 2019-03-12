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
     * @var int
     *
     * @ORM\Column(name="id_trad_elt", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="trad_elt_id_trad_elt_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var \Element
     *
     * @ORM\ManyToOne(targetEntity="Element")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_element", referencedColumnName="id")
     * })
     */
    private $element;

    public function getId(): ?int
    {
        return $this->id;
    }

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
