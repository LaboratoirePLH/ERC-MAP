<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Chercheur
 *
 * @ORM\Table(name="chercheur")
 * @ORM\Entity
 */
class Chercheur implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     */
    private $id;

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
     */
    private $mail;

    /**
     * @var string|null
     *
     * @ORM\Column(name="password", type="string", length=50, nullable=true)
     */
    private $password;

    /**
     * @var string|null
     *
     * @ORM\Column(name="preference_langue", type="string", length=2, nullable=true)
     */
    private $preferenceLangue;

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

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        if(strtolower($this->getRole()) === "admin"){
            $roles[] = 'ROLE_ADMIN';
        }
        return $roles;
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }
}
