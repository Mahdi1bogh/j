<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Avis
 *
 * @ORM\Table(name="avis", indexes={@ORM\Index(name="fk_avis", columns={"id_user"})})
 * @ORM\Entity
 */
class Avis
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_avis", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAvis;

    /**
     * @var string|null
     * @Assert\NotBlank()
     * @ORM\Column(name="text_avis", type="string", length=50, nullable=true, options={"default"="NULL"})
     */
    private $textAvis;

    /**
     * @var int|null
     *@Assert\NotBlank()
     * @ORM\Column(name="rating_avis", type="integer", nullable=true, options={"default"="NULL"})
     *@Assert\Range(
     *      min = 0,
     *      max = 5,
     *      notInRangeMessage = "Rating doit être entre {{ min }}  and {{ max }} ",
     * )
     */
    private $ratingAvis = NULL;

    /**
     * @var \Produits
     *
     * @ORM\ManyToOne(targetEntity="Produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_produit", referencedColumnName="id_produit")
     * })
     */
    private $idProduit;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    public function getIdAvis(): ?int
    {
        return $this->idAvis;
    }

    public function getTextAvis(): ?string
    {
        return $this->textAvis;
    }

    public function setTextAvis(?string $textAvis): self
    {
        $this->textAvis = $textAvis;

        return $this;
    }

    public function getRatingAvis(): ?int
    {
        return $this->ratingAvis;
    }

    public function setRatingAvis(?int $ratingAvis): self
    {
        $this->ratingAvis = $ratingAvis;

        return $this;
    }

    public function getIdProduit(): ?Produits
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produits $idProduit): self
    {
        $this->idProduit = $idProduit;

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

    public function __toString():string
    {
        return $this->getIdProduit();
    }


}
