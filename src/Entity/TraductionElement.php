<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TraductionElement
 *
 * @ORM\Table(name="trad_elt", indexes={@ORM\Index(name="IDX_DA870BC2B1D9A90D", columns={"id_elt"})})
 * @ORM\Entity
 */
class TraductionElement
{
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
     *   @ORM\JoinColumn(name="id_elt", referencedColumnName="id")
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
