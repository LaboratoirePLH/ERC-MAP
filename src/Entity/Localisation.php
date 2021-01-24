<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Localisation
 *
 * @ORM\Table(name="localisation")
 * @ORM\Entity(repositoryClass="App\Repository\LocalisationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Localisation extends AbstractEntity
{
    use Traits\EntityId;
    use Traits\TranslatedComment;

    /**
     * @var int|null
     *
     * @ORM\Column(name="pleiades_ville", type="integer", nullable=true)
     */
    private $pleiadesVille;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_ville", type="string", length=255, nullable=true)
     */
    private $nomVille;

    /**
     * @var float|null
     *
     * @ORM\Column(name="latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float|null
     *
     * @ORM\Column(name="longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var int|null
     *
     * @ORM\Column(name="pleiades_site", type="integer", nullable=true)
     */
    private $pleiadesSite;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_site", type="string", length=255, nullable=true)
     */
    private $nomSite;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="reel", type="boolean", nullable=true)
     */
    private $reel = true;

    /**
     * @var geometry|null
     *
     * @ORM\Column(name="geom", type="geometry", nullable=true, options={"geometry_type"="POINT", "srid"=4326})
     */
    private $geom;

    /**
     * @var EntitePolitique
     *
     * @ORM\ManyToOne(targetEntity="EntitePolitique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entite_politique", referencedColumnName="id")
     * })
     */
    private $entitePolitique;

    /**
     * @var GrandeRegion
     *
     * @ORM\ManyToOne(targetEntity="GrandeRegion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="grande_region_id", referencedColumnName="id")
     * })
     */
    private $grandeRegion;

    /**
     * @var SousRegion
     *
     * @ORM\ManyToOne(targetEntity="SousRegion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sous_region_id", referencedColumnName="id")
     * })
     */
    private $sousRegion;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="QTopographie")
     * @ORM\JoinTable(name="localisation_q_topographie",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_localisation", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_q_topographie", referencedColumnName="id")
     *   }
     * )
     */
    private $topographies;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="QFonction")
     * @ORM\JoinTable(name="localisation_q_fonction",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_localisation", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_q_fonction", referencedColumnName="id")
     *   }
     * )
     */
    private $fonctions;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->topographies = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fonctions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Clone magic method
     */
    public function __clone()
    {
        if ($this->id !== null) {
            $this->id = null;
        }
    }

    // Hack for localisation form
    public function setId(?int $id): self
    {
        return $this;
    }

    public function getPleiadesVille(): ?int
    {
        return $this->pleiadesVille;
    }

    public function setPleiadesVille(?int $pleiadesVille): self
    {
        $this->pleiadesVille = $pleiadesVille;

        return $this;
    }

    public function getNomVille(): ?string
    {
        return $this->nomVille;
    }

    public function setNomVille(?string $nomVille): self
    {
        $this->nomVille = $nomVille;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getPleiadesSite(): ?int
    {
        return $this->pleiadesSite;
    }

    public function setPleiadesSite(?int $pleiadesSite): self
    {
        $this->pleiadesSite = $pleiadesSite;

        return $this;
    }

    public function getNomSite(): ?string
    {
        return $this->nomSite;
    }

    public function setNomSite(?string $nomSite): self
    {
        $this->nomSite = $nomSite;

        return $this;
    }

    public function getReel(): ?bool
    {
        return $this->reel;
    }

    public function setReel(?bool $reel): self
    {
        $this->reel = $reel;

        return $this;
    }

    public function getGeom()
    {
        return $this->geom;
    }

    public function setGeom($geom): self
    {
        $this->geom = $geom;

        return $this;
    }

    public function getEntitePolitique(): ?EntitePolitique
    {
        return $this->entitePolitique;
    }

    public function setEntitePolitique(?EntitePolitique $entitePolitique): self
    {
        $this->entitePolitique = $entitePolitique;

        return $this;
    }

    public function getGrandeRegion(): ?GrandeRegion
    {
        return $this->grandeRegion;
    }

    public function setGrandeRegion(?GrandeRegion $grandeRegion): self
    {
        $this->grandeRegion = $grandeRegion;

        return $this;
    }

    public function getSousRegion(): ?SousRegion
    {
        return $this->sousRegion;
    }

    public function setSousRegion(?SousRegion $sousRegion): self
    {
        $this->sousRegion = $sousRegion;

        return $this;
    }

    public function setGrandeSousRegion($data): self
    {
        $this->setGrandeRegion($data['grandeRegion']);
        $this->setSousRegion($data['sousRegion']);
        return $this;
    }

    public function getGrandeSousRegion(): array
    {
        return [
            'sousRegion' => $this->getSousRegion(),
            'grandeRegion' => $this->getGrandeRegion(),
        ];
    }

    /**
     * @return Collection|QTopographie[]
     */
    public function getTopographies(): Collection
    {
        return $this->topographies;
    }

    public function addTopography(QTopographie $topographie): self
    {
        if (!$this->topographies->contains($topographie)) {
            $this->topographies[] = $topographie;
        }
        return $this;
    }

    public function removeTopography(QTopographie $topographie): self
    {
        if ($this->topographies->contains($topographie)) {
            $this->topographies->removeElement($topographie);
        }
        return $this;
    }

    /**
     * @return Collection|QFonction[]
     */
    public function getFonctions(): Collection
    {
        return $this->fonctions;
    }

    public function addFonction(QFonction $fonction): self
    {
        if (!$this->fonctions->contains($fonction)) {
            $this->fonctions[] = $fonction;
        }
        return $this;
    }

    public function removeFonction(QFonction $fonction): self
    {
        if ($this->fonctions->contains($fonction)) {
            $this->fonctions->removeElement($fonction);
        }
        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function _updateGeometry()
    {
        if (($lon = $this->getLongitude()) !== null && ($lat = $this->getLatitude()) !== null) {
            $this->setGeom("SRID=4326;POINT({$lon} {$lat})");
        }
    }

    public function isBlank(): bool
    {
        return !(!is_null($this->entitePolitique)
            || !is_null($this->grandeRegion)
            || !is_null($this->sousRegion)
            || !is_null($this->pleiadesVille)
            || (!is_null($this->nomVille) && strlen($this->nomVille) > 0)
            || !is_null($this->pleiadesSite)
            || (!is_null($this->nomSite) && strlen($this->nomSite) > 0)
            || !is_null($this->latitude)
            || !is_null($this->longitude)
            || !$this->topographies->isEmpty()
            || !$this->fonctions->isEmpty()
            || strlen($this->commentaireFr) > 0
            || strlen($this->commentaireEn) > 0);
    }

    public function toArray(): array
    {
        return [
            'id'              => $this->getId(),
            'reel'            => boolval($this->reel),
            'entitePolitique' => $this->entitePolitique === null ? null : $this->entitePolitique->toArray(),
            'grandeRegion'    => $this->grandeRegion === null ? null : $this->grandeRegion->toArray(),
            'sousRegion'      => $this->sousRegion === null ? null : $this->sousRegion->toArray(),
            'pleiadesVille'   => $this->pleiadesVille,
            'nomVille'        => $this->nomVille,
            'pleiadesSite'    => $this->pleiadesSite,
            'nomSite'         => $this->nomSite,
            'topographies'    => $this->topographies->map(function ($entry) {
                return $entry->toArray();
            })->getValues(),
            'fonctions'       => $this->fonctions->map(function ($entry) {
                return $entry->toArray();
            })->getValues(),
            'longitude'     => $this->longitude,
            'latitude'      => $this->latitude,
            'commentaireFr' => $this->commentaireFr,
            'commentaireEn' => $this->commentaireEn
        ];
    }

    private function _affichage($lang)
    {

        $base = [
            $this->grandeRegion !== null ? $this->grandeRegion->getNom($lang) : '',
            $this->sousRegion !== null ? $this->sousRegion->getNom($lang) : ''
        ];
        if ($this->nomVille !== null) {
            $base[] = $this->nomVille . ($this->pleiadesVille !== null ? '(#' . $this->pleiadesVille . ')' : '');
        }
        if ($this->nomSite !== null) {
            $base[] = $this->nomSite . ($this->pleiadesSite !== null ? '(#' . $this->pleiadesSite . ')' : '');
        }
        $base = implode(' > ', $base);
        $base = str_replace('>  >', '>>', $base);
        $base = trim($base, "> ");

        if (strlen($base) > 0 && !is_null($this->entitePolitique)) {
            $base = $base . ' - ' . $this->entitePolitique->getAffichage($lang);
        }
        if ($this->grandeRegion !== null && $this->grandeRegion->getId() === 18) {
            $topographies = $this->topographies->map(function ($t) use ($lang) {
                return $t->getNom($lang);
            })->toArray();
            $fonctions = $this->fonctions->map(function ($t) use ($lang) {
                return $t->getNom($lang);
            })->toArray();
            if (!empty($topographies) || !empty($fonctions)) {
                $base .= ' { ' . implode(
                    ' || ',
                    array_map(
                        function ($arr) {
                            return implode(', ', $arr);
                        },
                        array_filter([
                            $topographies,
                            $fonctions
                        ])
                    )
                ) . ' }';
            }
        }

        return  $base . ' [#' . $this->id . ']';
    }

    public function getAffichageFr(): string
    {
        return $this->_affichage('fr');
    }

    public function getAffichageEn(): string
    {
        return $this->_affichage('en');
    }
}
