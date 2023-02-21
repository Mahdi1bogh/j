<?php

namespace App\Entity;

use App\Repository\TournoisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TournoisRepository::class)
 */
class Tournois
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */

    private $id_tournois;

    /**
     * @ORM\Column(type="string", length=255)
     *  * @Assert\NotBlank
     */
    private $nom_tournois;

    /**
     * @ORM\Column(type="string", length=255)
     *  * @Assert\NotBlank
     */
    private $rank_tournois;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * * @Assert\Date
     * @var string A "d-m-Y" formatted value
     */
    private $date_tournois;

//    /**
//     * @ORM\Column(type="integer")
//     *  * @Assert\NotBlank
//     *
//     */
//    private $id_match;



    public function getIdTournois(): ?int
    {
        return $this->id_tournois;
    }

    public function setIdTournois(int $id_tournois): self
    {
        $this->id_tournois = $id_tournois;

        return $this;
    }

    public function getNomTournois(): ?string
    {
        return $this->nom_tournois;
    }

    public function setNomTournois(string $nom_tournois): self
    {
        $this->nom_tournois = $nom_tournois;

        return $this;
    }

    public function getRankTournois(): ?string
    {
        return $this->rank_tournois;
    }

    public function setRankTournois(string $rank_tournois): self
    {
        $this->rank_tournois = $rank_tournois;

        return $this;
    }

    public function getDateTournois(): ?string
    {
        return $this->date_tournois;
    }

    public function setDateTournois(string $date_tournois): self
    {
        $this->date_tournois = $date_tournois;

        return $this;
    }
//
//    public function getIdMatch(): ?int
//    {
//        return $this->id_match;
//    }

//    public function setIdMatch(int $id_match): self
//    {
//        $this->id_match = $id_match;
//
//        return $this;
//    }
}
