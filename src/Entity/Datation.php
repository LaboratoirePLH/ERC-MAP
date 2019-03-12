<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Datation
 *
 * @ORM\Table(name="datation")
 * @ORM\Entity
 */
class Datation
{
    use Traits\EntityId;
    use Traits\TranslatedComment;

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
     * @Assert\Expression(
     *      "this.hasValidDates()",
     *      message="datation_error"
     * )
     */
    private $anteQuem;

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

    public function hasValidDates(): bool
    {
        return $this->getPostQuem() === null
            || $this->getAnteQuem() === null
            || $this->getPostQuem() <= $this->getAnteQuem();
    }
}
