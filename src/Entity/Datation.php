<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Datation
 *
 * @ORM\Table(name="datation")
 * @ORM\Entity
 */
class Datation
{
    use Traits\TranslatedComment;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="post_quem", type="smallint", nullable=true)
     */
    private $postQuem;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ante_quem", type="smallint", nullable=true)
     */
    private $anteQuem;

    /**
     * @var int|null
     *
     * @ORM\Column(name="post_quem_citation", type="smallint", nullable=true)
     */
    private $postQuemCitation;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ante_quem_citation", type="smallint", nullable=true)
     */
    private $anteQuemCitation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dateAncienne", type="text", nullable=true)
     */
    private $dateAncienne;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostQuem(): ?int
    {
        return $this->postQuem;
    }

    public function setPostQuem(?int $postQuem): self
    {
        $this->postQuem = $postQuem;

        return $this;
    }

    public function getAnteQuem(): ?int
    {
        return $this->anteQuem;
    }

    public function setAnteQuem(?int $anteQuem): self
    {
        $this->anteQuem = $anteQuem;

        return $this;
    }

    public function getPostQuemCitation(): ?int
    {
        return $this->postQuemCitation;
    }

    public function setPostQuemCitation(?int $postQuemCitation): self
    {
        $this->postQuemCitation = $postQuemCitation;

        return $this;
    }

    public function getAnteQuemCitation(): ?int
    {
        return $this->anteQuemCitation;
    }

    public function setAnteQuemCitation(?int $anteQuemCitation): self
    {
        $this->anteQuemCitation = $anteQuemCitation;

        return $this;
    }

    public function getDateAncienne(): ?string
    {
        return $this->dateAncienne;
    }

    public function setDateAncienne(?string $dateAncienne): self
    {
        $this->dateAncienne = $dateAncienne;

        return $this;
    }
}
