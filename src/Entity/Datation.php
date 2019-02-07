<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Datation
 *
 * @ORM\Table(name="datation")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 */
class Datation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="datation_id_datation_seq", allocationSize=1, initialValue=1)
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
     * @ORM\Column(name="post_quem_cit", type="smallint", nullable=true)
     */
    private $postQuemCit;

    /**
     * @var int|null
     *
     * @ORM\Column(name="ante_quem_cit", type="smallint", nullable=true)
     */
    private $anteQuemCit;

    /**
     * @var string|null
     *
     * @ORM\Column(name="date_anc", type="text", nullable=true)
     */
    private $dateAnc;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_date", type="text", nullable=true)
     */
    private $commentaireDateFr;

    /**
     * @var string|null
     *
     * @ORM\Column(name="com_date_en", type="text", nullable=true)
     */
    private $commentaireDateEn;

    /**
     * @var int|null
     *
     * @ORM\Column(name="fiab_datation", type="smallint", nullable=true)
     */
    private $fiabiliteDatation;

    /**
     * @ORM\PreUpdate
     */
    public function updateFiabilite(): void
    {
        $this->setFiabiliteDatation(abs($this->getPostQuem() - $this->getAnteQuem()));
    }

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

    public function getPostQuemCit(): ?int
    {
        return $this->postQuemCit;
    }

    public function setPostQuemCit(?int $postQuemCit): self
    {
        $this->postQuemCit = $postQuemCit;

        return $this;
    }

    public function getAnteQuemCit(): ?int
    {
        return $this->anteQuemCit;
    }

    public function setAnteQuemCit(?int $anteQuemCit): self
    {
        $this->anteQuemCit = $anteQuemCit;

        return $this;
    }

    public function getDateAnc(): ?string
    {
        return $this->dateAnc;
    }

    public function setDateAnc(?string $dateAnc): self
    {
        $this->dateAnc = $dateAnc;

        return $this;
    }

    public function getCommentaireDateFr(): ?string
    {
        return $this->commentaireDateFr;
    }

    public function setCommentaireDateFr(?string $commentaireDateFr): self
    {
        $this->commentaireDateFr = $commentaireDateFr;

        return $this;
    }

    public function getCommentaireDateEn(): ?string
    {
        return $this->commentaireDateEn;
    }

    public function setCommentaireDateEn(?string $commentaireDateEn): self
    {
        $this->commentaireDateEn = $commentaireDateEn;

        return $this;
    }

    public function getFiabiliteDatation(): ?int
    {
        return $this->fiabiliteDatation;
    }

    public function setFiabiliteDatation(?int $fiabiliteDatation): self
    {
        $this->fiabiliteDatation = $fiabiliteDatation;

        return $this;
    }


}
