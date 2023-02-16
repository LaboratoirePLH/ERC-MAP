<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Formule
 *
 * @ORM\Table(name="requete_enregistree")
 * @ORM\Entity
 */
class RequeteEnregistree extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedName;
    use Traits\TranslatedComment;

    /**
     * @var string
     *
     * @ORM\Column(name="query", type="text", nullable=false)
     */
    private $query;

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function toArray(?string $lang): array
    {
        return [
            'id'          => $this->id,
            'query'       => $this->query,
            'description' => $this->getCommentaire($lang)
        ];
    }
}
