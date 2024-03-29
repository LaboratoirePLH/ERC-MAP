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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $projet_id;

    public function getProjetId(): ?int
    {
        return $this->projet_id;
    }

    public function setProjetId(?int $projet_id = null): self
    {
        $this->projet_id = $projet_id;
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
        array_walk_recursive($data, function ($value, $key) use (&$textData) {
            if (!in_array($key, ['id', 'elementIds', 'source', 'attestations']) && !is_bool($value)) {
                if (!is_numeric($value)) {
                    // First replace <br/> tags by newliens to keep the word boundaries
                    $value = preg_replace('/\<br(\s*)?\/?\>/i', "\n", $value);
                    // Remove tags and accents and convert to lower case
                    $value = strtolower(\App\Utils\StringHelper::removeAccents(strip_tags($value)));
                }
                $textData[] = $value;
            }
        });
        $this->setTextData($textData);

        return $this;
    }

    /**
     * @ORM\Column(name="text_data", type="text", nullable=true, options={"collation":"utf8_bin"})
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

    /**
     * @var bool
     *
     * @ORM\Column(name="corpus_ready", type="boolean", nullable=false, options={"default" : false})
     */
    private $corpusReady = false;

    public function getCorpusReady(): bool
    {
        return $this->corpusReady;
    }

    public function setCorpusReady(bool $corpusReady): self
    {
        $this->corpusReady = $corpusReady;
        return $this;
    }
}
