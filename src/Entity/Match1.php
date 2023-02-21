<?php

namespace App\Entity;

use App\Repository\Match1Repository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=Match1Repository::class)
 */
class Match1
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")

     */

    private $id_match1;

    /**
     * @ORM\Column(type="string", length=255)
     *  * @Assert\NotBlank
     */
    private $nom_equipe1;

    /**
     * @ORM\Column(type="string", length=255)
     *  * @Assert\NotBlank
     * @Assert\NotEqualTo(propertyPath="nom_equipe1",
     * message = "l'equipe 1 ne doit pas etre identique a l'equipe 2."
     * )
     */
    private $nom_equipe2;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Date
     * @var string A "d-m-Y" formatted value
     */

    private $date_match1;

    /**
     * @ORM\Column(type="string", length=255)
     *
     *
     */
    private $resultat_match1;

    /**
     * @ORM\Column(type="string", length=255)
     *  * @Assert\NotBlank
     */
    private $lieu_match1;

    /**
     * @ORM\Column(type="string", length=255)
     *
     *   * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     *   @Assert\NotBlank
     */
    private $Email_lieu;


    public function getIdMatch1(): ?int
    {
        return $this->id_match1;
    }

    public function setIdMatch1(int $id_match1): self
    {
        $this->id_match1 = $id_match1;

        return $this;
    }

    public function getNomEquipe1(): ?string
    {
        return $this->nom_equipe1;
    }

    public function setNomEquipe1(string $nom_equipe1): self
    {
        $this->nom_equipe1 = $nom_equipe1;

        return $this;
    }

    public function getNomEquipe2(): ?string
    {
        return $this->nom_equipe2;
    }

    public function setNomEquipe2(string $nom_equipe2): self
    {
        $this->nom_equipe2 = $nom_equipe2;

        return $this;
    }

    public function getDateMatch1(): ?string
    {
        return $this->date_match1;
    }

    public function setDateMatch1(string $date_match1): self
    {
        $this->date_match1 = $date_match1;

        return $this;
    }

    public function getResultatMatch1(): ?string
    {
        return $this->resultat_match1;
    }

    public function setResultatMatch1(string $resultat_match1): self
    {
        $this->resultat_match1 = $resultat_match1;

        return $this;
    }

    public function getLieuMatch1(): ?string
    {
        return $this->lieu_match1;
    }

    public function setLieuMatch1(string $lieu_match1): self
    {
        $this->lieu_match1 = $lieu_match1;

        return $this;
    }

    public function getEmailLieu(): ?string
    {
        return $this->Email_lieu;
    }

    public function setEmailLieu(string $Email_lieu): self
    {
        $this->Email_lieu = $Email_lieu;

        return $this;
    }
}
