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
}
