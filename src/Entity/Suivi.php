<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="suivi")
 * @ORM\Entity
 */
class Suivi
{
    use Traits\EntityId;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nom_table;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_entite;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_heure;

    /**
     * @ORM\Column(type="string", length=6)
     */
    private $action;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $old_data;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $new_data;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $detail;

    public function getNomTable(): ?string
    {
        return $this->nom_table;
    }

    public function setNomTable(string $nom_table): self
    {
        $this->nom_table = $nom_table;

        return $this;
    }

    public function getDateHeure(): ?\DateTimeInterface
    {
        return $this->date_heure;
    }

    public function setDateHeure(\DateTimeInterface $date_heure): self
    {
        $this->date_heure = $date_heure;

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

    public function getOldData(): ?string
    {
        return $this->old_data;
    }

    public function setOldData(?string $old_data): self
    {
        $this->old_data = $old_data;

        return $this;
    }

    public function getNewData(): ?string
    {
        return $this->new_data;
    }

    public function setNewData(?string $new_data): self
    {
        $this->new_data = $new_data;

        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): self
    {
        $this->detail = $detail;

        return $this;
    }

    public function getIdEntite(): ?int
    {
        return $this->id_entite;
    }

    public function setIdEntite(?int $id_entite): self
    {
        $this->id_entite = $id_entite;

        return $this;
    }
}
