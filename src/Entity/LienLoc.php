<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LienLoc
 *
 * @ORM\Table(name="lien_loc", indexes={@ORM\Index(name="idx_lien_loc_id_source", columns={"id_source"}), @ORM\Index(name="fki_type_loc_fkey", columns={"type_loc"}), @ORM\Index(name="idx_lien_loc_id_loc", columns={"id_loc"})})
 * @ORM\Entity
 */
class LienLoc
{
    /**
     * @var \Localisation
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Localisation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_loc", referencedColumnName="id")
     * })
     */
    private $idLoc;

    /**
     * @var \Source
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Source")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_source", referencedColumnName="id")
     * })
     */
    private $idSource;

    /**
     * @var \LocType
     *
     * @ORM\ManyToOne(targetEntity="LocType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_loc", referencedColumnName="id_loc_type")
     * })
     */
    private $typeLoc;

    public function getIdLoc(): ?Localisation
    {
        return $this->idLoc;
    }

    public function setIdLoc(?Localisation $idLoc): self
    {
        $this->idLoc = $idLoc;

        return $this;
    }

    public function getIdSource(): ?Source
    {
        return $this->idSource;
    }

    public function setIdSource(?Source $idSource): self
    {
        $this->idSource = $idSource;

        return $this;
    }

    public function getTypeLoc(): ?LocType
    {
        return $this->typeLoc;
    }

    public function setTypeLoc(?LocType $typeLoc): self
    {
        $this->typeLoc = $typeLoc;

        return $this;
    }


}
