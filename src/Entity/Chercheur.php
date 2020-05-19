<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Chercheur
 *
 * @ORM\Table(name="chercheur")
 * @ORM\Entity(repositoryClass="App\Repository\ChercheurRepository")
 * @UniqueEntity(
 *     fields={"mail"},
 *     message="email_duplicate"
 * )
 * @UniqueEntity(
 *     fields={"username"},
 *     message="username_duplicate"
 * )
 */
class Chercheur extends AbstractEntity implements UserInterface
{
    use Traits\EntityId;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom_nom", type="string", length=255, nullable=false)
     */
    private $prenomNom;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=150, nullable=false)
     */
    private $username;

    /**
     * @var string|null
     *
     * @ORM\Column(name="institution", type="string", length=250, nullable=true)
     */
    private $institution;

    /**
     * @var string|null
     *
     * @ORM\Column(name="mail", type="string", length=250, nullable=true)
     * @Assert\Email(message="invalid_email_format")
     */
    private $mail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="preference_langue", type="string", length=2, nullable=true)
     */
    private $preferenceLangue = "fr";

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_ajout", type="date", nullable=true)
     */
    private $dateAjout;

    /**
     * @var string|null
     *
     * @ORM\Column(name="role", type="string", length=50, nullable=true)
     */
    private $role = "user";

    /**
     * @var string|null
     *
     * @ORM\Column(name="reset_token", type="string", length=255, nullable=true)
     */
    private $resetToken;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Projet", mappedBy="chercheurs")
     */
    private $projets;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VerrouEntite", mappedBy="createur", orphanRemoval=true)
     */
    private $verrous;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Requetes", mappedBy="id_chercheur", orphanRemoval=true)
     */
    private $requetes; //Ici ça va être un tableau de requêtes en fait

    /**
     * @var bool
     *
     * @ORM\Column(name="actif", type="boolean", nullable=false)
     */
    private $actif = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="gestionnaire_comptes", type="boolean", nullable=false)
     */
    private $gestionnaireComptes = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->projets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->verrous = new ArrayCollection();
        $this->requetes = new ArrayCollection();

        $this->setDateAjout(new \DateTime());
    }

    public function getPrenomNom(): ?string
    {
        return $this->prenomNom;
    }

    public function setPrenomNom(string $prenomNom): self
    {
        $this->prenomNom = $prenomNom;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getInstitution(): ?string
    {
        return $this->institution;
    }

    public function setInstitution(?string $institution): self
    {
        $this->institution = $institution;
        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    private $newPassword;

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(?string $newPassword): self
    {
        $this->newPassword = $newPassword;
        return $this;
    }

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->dateAjout;
    }

    public function setDateAjout(?\DateTimeInterface $dateAjout): self
    {
        $this->dateAjout = $dateAjout;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    public function getRoles(): array
    {
        if (!$this->getActif()) {
            return ['ROLE_UNVERIFIED_USER'];
        }
        $roles = ['ROLE_USER'];
        if (strtolower($this->getRole()) === "admin") {
            $roles[] = 'ROLE_ADMIN';
            $roles[] = 'ROLE_MODERATOR';
            $roles[] = 'ROLE_CONTRIBUTOR';
        }
        if (strtolower($this->getRole()) === "moderator") {
            $roles[] = 'ROLE_MODERATOR';
            $roles[] = 'ROLE_CONTRIBUTOR';
        }
        if (strtolower($this->getRole()) === "contributor") {
            $roles[] = 'ROLE_CONTRIBUTOR';
        }
        return $roles;
    }

    /**
     * @return Collection|Projet[]
     */
    public function getProjets(): ?Collection
    {
        return $this->projets;
    }

    public function addProjet(Projet $projet): self
    {
        if (!$this->projets->contains($projet)) {
            $this->projets[] = $projet;
            $projet->addChercheur($this);
        }

        return $this;
    }

    public function removeProjet(Projet $projet): self
    {
        if ($this->projets->contains($projet)) {
            $this->projets->removeElement($projet);
            $projet->removeChercheur($this);
        }

        return $this;
    }

    public function getNomsProjets(): string
    {
        $projets = [];
        foreach ($this->getProjets() as $p) {
            $projets[] = $p->getNomFr();
        }
        return implode(', ', $projets);
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    public function getPreferenceLangue(): ?string
    {
        return $this->preferenceLangue;
    }

    public function setPreferenceLangue(?string $preferenceLangue): self
    {
        $this->preferenceLangue = $preferenceLangue;

        return $this;
    }

    /**
     * @return Collection|VerrouEntite[]
     */
    public function getVerrous(): Collection
    {
        return $this->verrous;
    }

    public function addVerrous(VerrouEntite $verrous): self
    {
        if (!$this->verrous->contains($verrous)) {
            $this->verrous[] = $verrous;
            $verrous->setCreateur($this);
        }

        return $this;
    }

    public function removeVerrous(VerrouEntite $verrous): self
    {
        if ($this->verrous->contains($verrous)) {
            $this->verrous->removeElement($verrous);
            // set the owning side to null (unless already changed)
            if ($verrous->getCreateur() === $this) {
                $verrous->setCreateur(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getPrenomNom();
    }

    /**
     * @return Collection|Requetes[]
     */
    public function getRequetes(): Collection
    {
        return $this->requetes;
    }

    public function addRequete(Requetes $requete): self
    {
        if (!$this->requetes->contains($requete)) {
            $this->requetes[] = $requete;
            $requete->setIdChercheur($this);
        }

        return $this;
    }

    public function removeRequete(Requetes $requete): self
    {
        if ($this->requetes->contains($requete)) {
            $this->requetes->removeElement($requete);
            // set the owning side to null (unless already changed)
            if ($requete->getIdChercheur() === $this) {
                $requete->setIdChercheur(null);
            }
        }

        return $this;
    }

    public function getActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;
        return $this;
    }

    public function getGestionnaireComptes(): bool
    {
        return $this->gestionnaireComptes;
    }

    public function setGestionnaireComptes(bool $gestionnaireComptes): self
    {
        $this->gestionnaireComptes = $gestionnaireComptes;
        return $this;
    }
}
