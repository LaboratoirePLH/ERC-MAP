<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TradElt
 *
 * @ORM\Table(name="trad_elt", indexes={@ORM\Index(name="IDX_DA870BC2B1D9A90D", columns={"id_elt"})})
 * @ORM\Entity
 */
class TradElt
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_trad_elt", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="trad_elt_id_trad_elt_seq", allocationSize=1, initialValue=1)
     */
    private $idTradElt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=100, nullable=true)
     */
    private $name;

    /**
     * @var \Elements
     *
     * @ORM\ManyToOne(targetEntity="Elements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_elt", referencedColumnName="id")
     * })
     */
    private $idElt;

    public function getIdTradElt(): ?int
    {
        return $this->idTradElt;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIdElt(): ?Elements
    {
        return $this->idElt;
    }

    public function setIdElt(?Elements $idElt): self
    {
        $this->idElt = $idElt;

        return $this;
    }


}
