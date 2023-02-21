<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Joueur
 *
 * @ORM\Table(name="joueur")
 * @ORM\Entity
 */
class Joueur
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_joueur", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idJoueur;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="nom_joueur", type="string", length=30, nullable=false)
     */
    private $nomJoueur;

    /**
     * @var string
     *@Assert\NotBlank
     * @ORM\Column(name="prenom_joueur", type="string", length=30, nullable=false)
     */
    private $prenomJoueur;

    /**
     * @var int
     *@Assert\NotBlank
     * @ORM\Column(name="age_joueur", type="integer", nullable=false)
     */
    private $ageJoueur;

    /**
     * @var string
     * @Assert\NotBlank
     * @Assert\Email
     * @ORM\Column(name="email_joueur", type="string", length=30, nullable=false)
     */
    private $emailJoueur;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    /**
     * @return int
     */
    public function getIdJoueur(): int
    {
        return $this->idJoueur;
    }

    /**
     * @param int $idJoueur
     */
    public function setIdJoueur(int $idJoueur): void
    {
        $this->idJoueur = $idJoueur;
    }

    /**
     * @return string
     */
    public function getNomJoueur(): ?string
    {
        return $this->nomJoueur;
    }

    /**
     * @param string $nomJoueur
     */
    public function setNomJoueur(string $nomJoueur): void
    {
        $this->nomJoueur = $nomJoueur;
    }

    /**
     * @return string
     */
    public function getPrenomJoueur(): ?string
    {
        return $this->prenomJoueur;
    }

    /**
     * @param string $prenomJoueur
     */
    public function setPrenomJoueur(string $prenomJoueur): void
    {
        $this->prenomJoueur = $prenomJoueur;
    }

    /**
     * @return int
     */
    public function getAgeJoueur(): ?int
    {
        return $this->ageJoueur;
    }

    /**
     * @param int $ageJoueur
     */
    public function setAgeJoueur(int $ageJoueur): void
    {
        $this->ageJoueur = $ageJoueur;
    }

    /**
     * @return string
     */
    public function getEmailJoueur(): ?string
    {
        return $this->emailJoueur;
    }

    /**
     * @param string $emailJoueur
     */
    public function setEmailJoueur(string $emailJoueur): void
    {
        $this->emailJoueur = $emailJoueur;
    }

    /**
     * @return ?User
     */
    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    /**
     * @param ?User $idUser
     */
    public function setIdUser(?User $idUser): void
    {
        $this->idUser = $idUser;
    }



    public function __toString(): string
    {
        return $this->getIdJoueur().' | '.$this->getNomJoueur().' '.$this->getPrenomJoueur();
    }


}
