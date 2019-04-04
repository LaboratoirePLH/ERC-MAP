<?php

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TranslatedComment
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="commentaire_fr", type="text", nullable=true)
     */
    private $commentaireFr;

    public function getCommentaireFr(): ?string
    {
        return $this->commentaireFr;
    }

    public function setCommentaireFr(?string $commentaireFr): self
    {
        $this->commentaireFr = $commentaireFr;
        return $this;
    }

    /**
     * @var string|null
     *
     * @ORM\Column(name="commentaire_en", type="text", nullable=true)
     */
    private $commentaireEn;

    public function getCommentaireEn(): ?string
    {
        return $this->commentaireEn;
    }

    public function setCommentaireEn(?string $commentaireEn): self
    {
        $this->commentaireEn = $commentaireEn;
        return $this;
    }

    public function getCommentaire(?string $lang): ?string
    {
        if($lang == 'fr'){
            return $this->commentaireFr;
        } else {
            return $this->commentaireEn;
        }
    }
}