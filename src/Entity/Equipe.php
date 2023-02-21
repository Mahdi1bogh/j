<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Equipe
 *
 * @ORM\Table(name="equipe")
 * @ORM\Entity
 */
class Equipe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_equipe", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEquipe;

    /**
     * @var string
     *@Assert\NotBlank()
     * @ORM\Column(name="nom_equipe", type="string", length=30, nullable=false)
     */
    private $nomEquipe;

    /**
     * @var string
     *@Assert\NotBlank
     * @ORM\Column(name="jeu_equipe", type="string", length=30, nullable=false)
     */
    private $jeuEquipe;

    /**
     * @var string
     * @ORM\Column(name="logo_equipe", type="string", length=100, nullable=false)
     */
    private $logoEquipe;

    /**
     * @var \Joueur
     *@Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_J1", referencedColumnName="id_joueur")
     * })
     */
    private $idJ1;

    /**
     * @var \Joueur
     *@Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_J2", referencedColumnName="id_joueur")
     * })
     */
    private $idJ2;

    /**
     * @var \Joueur
     *@Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_J3", referencedColumnName="id_joueur")
     * })
     */
    private $idJ3;

    /**
     * @var \Joueur
     *@Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_J4", referencedColumnName="id_joueur")
     * })
     */
    private $idJ4;

    /**
     * @var \Joueur
     *@Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="Joueur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_J5", referencedColumnName="id_joueur")
     * })
     */
    private $idJ5;

    /**
     * @return int
     */
    public function getIdEquipe(): int
    {
        return $this->idEquipe;
    }

    /**
     * @param int $idEquipe
     */
    public function setIdEquipe(int $idEquipe): void
    {
        $this->idEquipe = $idEquipe;
    }

    /**
     * @return string
     */
    public function getNomEquipe(): ?string
    {
        return $this->nomEquipe;
    }

    /**
     * @param string $nomEquipe
     */
    public function setNomEquipe(string $nomEquipe): void
    {
        $this->nomEquipe = $nomEquipe;
    }

    /**
     * @return string
     */
    public function getJeuEquipe(): ?string
    {
        return $this->jeuEquipe;
    }

    /**
     * @param string $jeuEquipe
     */
    public function setJeuEquipe(string $jeuEquipe): void
    {
        $this->jeuEquipe = $jeuEquipe;
    }


    public function getLogoEquipe()
    {
        return $this->logoEquipe;
    }


    public function setLogoEquipe( $logoEquipe)
    {
        $this->logoEquipe = $logoEquipe;
        return $this ;
    }

//    /**
//     *  @var \Joueur
//     *
//     * @ORM\ManyToOne(targetEntity="Joueur")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="id_joueur", referencedColumnName="id__joueur")
//     * })
//     */
    public function getIdJ1(): ?Joueur
    {
        return $this->idJ1;
    }
//
//    /**
//     * @param int $idJ1
//     */
    public function setIdJ1(Joueur $idJ1): void
    {
        $this->idJ1 = $idJ1;
    }

    public function getIdJ2(): ?Joueur
    {
        return $this->idJ2;
    }

    public function setIdJ2(Joueur $idJ2): void
    {
        $this->idJ2 = $idJ2;
    }

    public function getIdJ3(): ?Joueur
    {
        return $this->idJ3;
    }

    public function setIdJ3(Joueur $idJ3): void
    {
        $this->idJ3 = $idJ3;
    }

    public function getIdJ4(): ?Joueur
    {
        return $this->idJ4;
    }

    public function setIdJ4(Joueur $idJ4): void
    {
        $this->idJ4 = $idJ4;
    }


    public function getIdJ5(): ?JOueur
    {
        return $this->idJ5;
    }

    public function setIdJ5(Joueur $idJ5): void
    {
        $this->idJ5 = $idJ5;
    }

    public function __toString() :String
    {
        return $this->getNomEquipe();
    }


}
