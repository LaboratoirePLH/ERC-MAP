<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IndexRechercheRepository")
 */
class IndexRecherche
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", nullable=false)
     */
    private $entite;

    public function getEntite(): string
    {
        return $this->entite;
    }

    public function setEntite(string $entite): self
    {
        $this->entite = $entite;
        return $this;
    }

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @ORM\Column(type="text", nullable=false, options={"collation":"utf8_bin"})
     */
    private $data;

    private $decodedData = null;

    public function getData(): array
    {
        if(!$this->decodedData){
            $this->decodedData = json_decode($this->data, true);
        }
        return $this->decodedData;
    }

    public function setData(array $data): self
    {
        $this->data = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $this;
    }
}
