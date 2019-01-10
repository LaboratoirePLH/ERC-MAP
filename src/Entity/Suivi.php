<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Suivi
 *
 * @ORM\Table(name="suivi", indexes={@ORM\Index(name="index_suivi_idobjet", columns={"idobjet"}), @ORM\Index(name="index_suivi_action", columns={"action"}), @ORM\Index(name="index_suivi_dateheure", columns={"dateheure"})})
 * @ORM\Entity
 */
class Suivi
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="suivi_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="schema", type="string", length=15, nullable=false)
     */
    private $schema;

    /**
     * @var string
     *
     * @ORM\Column(name="nomtable", type="string", length=50, nullable=false)
     */
    private $nomtable;

    /**
     * @var string|null
     *
     * @ORM\Column(name="utilisateur", type="string", length=100, nullable=true)
     */
    private $utilisateur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateheure", type="datetime", nullable=false, options={"default"="now"})
     */
    private $dateheure = 'now';

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=1, nullable=false)
     */
    private $action;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dataorigine", type="text", nullable=true)
     */
    private $dataorigine;

    /**
     * @var string|null
     *
     * @ORM\Column(name="datanouvelle", type="text", nullable=true)
     */
    private $datanouvelle;

    /**
     * @var string|null
     *
     * @ORM\Column(name="detailmaj", type="text", nullable=true)
     */
    private $detailmaj;

    /**
     * @var int|null
     *
     * @ORM\Column(name="idobjet", type="integer", nullable=true)
     */
    private $idobjet;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSchema(): ?string
    {
        return $this->schema;
    }

    public function setSchema(string $schema): self
    {
        $this->schema = $schema;

        return $this;
    }

    public function getNomtable(): ?string
    {
        return $this->nomtable;
    }

    public function setNomtable(string $nomtable): self
    {
        $this->nomtable = $nomtable;

        return $this;
    }

    public function getUtilisateur(): ?string
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?string $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getDateheure(): ?\DateTimeInterface
    {
        return $this->dateheure;
    }

    public function setDateheure(\DateTimeInterface $dateheure): self
    {
        $this->dateheure = $dateheure;

        return $this;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getDataorigine(): ?string
    {
        return $this->dataorigine;
    }

    public function setDataorigine(?string $dataorigine): self
    {
        $this->dataorigine = $dataorigine;

        return $this;
    }

    public function getDatanouvelle(): ?string
    {
        return $this->datanouvelle;
    }

    public function setDatanouvelle(?string $datanouvelle): self
    {
        $this->datanouvelle = $datanouvelle;

        return $this;
    }

    public function getDetailmaj(): ?string
    {
        return $this->detailmaj;
    }

    public function setDetailmaj(?string $detailmaj): self
    {
        $this->detailmaj = $detailmaj;

        return $this;
    }

    public function getIdobjet(): ?int
    {
        return $this->idobjet;
    }

    public function setIdobjet(?int $idobjet): self
    {
        $this->idobjet = $idobjet;

        return $this;
    }


}
