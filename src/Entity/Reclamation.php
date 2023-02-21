<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reclam", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReclam;

    /**
     * @var string
     *
     * @ORM\Column(name="type_reclam", type="string", length=50, nullable=false)
     */
    private $typeReclam;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="motif_reclam", type="string", length=50, nullable=false)
     */
    private $motifReclam;

    /**
     * @var string
     *
     * @ORM\Column(name="etat_reclam", type="string", length=50, nullable=false)
     */
    private $etatReclam;

    /**
     * @var string
     *  @Assert\NotBlank()
     * @ORM\Column(name="message_reclam", type="string", length=255, nullable=false)
     */
    private $messageReclam;

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
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_reclam", type="datetime", nullable=true)
     */
    private $dateReclam;

    public function getIdReclam(): ?int
    {
        return $this->idReclam;
    }

    public function getTypeReclam(): ?string
    {
        return $this->typeReclam;
    }

    public function setTypeReclam(string $typeReclam): self
    {
        $this->typeReclam = $typeReclam;

        return $this;
    }

    public function getMotifReclam(): ?string
    {
        return $this->motifReclam;
    }

    public function setMotifReclam(?string $motifReclam): self
    {
        $this->motifReclam = $motifReclam;

        return $this;
    }

    public function getEtatReclam(): ?string
    {
        return $this->etatReclam;
    }

    public function setEtatReclam(?string $etatReclam): self
    {
        $this->etatReclam = $etatReclam;

        return $this;
    }

    public function getMessageReclam(): ?string
    {
        return $this->messageReclam;
    }

    public function setMessageReclam(?string $messageReclam): self
    {
        $this->messageReclam = $messageReclam;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getDateReclam(): ?\DateTimeInterface
    {
        return $this->dateReclam;
    }

    public function setDateReclam(): self
    {
        $current = new \DateTime();
        $current->modify('+ 1 Hour');
        $this->dateReclam = $current;

        return $this;
    }


}
