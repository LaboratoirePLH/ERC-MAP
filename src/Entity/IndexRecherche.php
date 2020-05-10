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
        if (!$this->decodedData) {
            $this->decodedData = json_decode($this->data, true);
        }
        return $this->decodedData;
    }

    public function setData(array $data): self
    {
        $this->data = json_encode($data, JSON_UNESCAPED_UNICODE);

        $textData = [];
        array_walk_recursive($data, function ($i) use (&$textData) {
            if (!is_numeric($i)) {
                // First replace <br/> tags by newliens to keep the word boundaries
                $i = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $i);
                // Remove tags and accents and convert to lower case
                $i = strtolower(\App\Utils\StringHelper::removeAccents(strip_tags($i)));
            }
            $textData[] = $i;
        });
        $this->setTextData($textData);

        return $this;
    }

    /**
     * @ORM\Column(name="text_data", type="text", nullable=false, options={"collation":"utf8_bin"})
     */
    private $textData;

    public function getTextData(): array
    {
        return json_decode($this->textData);
    }

    public function setTextData(array $textData): self
    {
        $this->textData = json_encode($textData, JSON_UNESCAPED_UNICODE);
        return $this;
    }
}
