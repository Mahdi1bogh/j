<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Categorie;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=ProduitsRepository::class)
 * @Vich\Uploadable
 */
class Produits
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     */
    private $id_produit;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * 
     */
    private $nom_produit;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotEqualTo("0")
     * @Assert\NotBlank()
     * 
     */
    private $nb_pts;

     /**
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="id_categorie")
     * })
      *
     * 
     */
    private $id_categorie;

    /**
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=1000)
     * @var string
     *
     */
    private $image;



    /**
     * @Assert\NotEqualTo("0")
     * @ORM\Column(type="integer")
     * 
     */
    private $quantite;

    public function getIdProduit(): ?int
    {
        return $this->id_produit;
    }


    public function getNomProduit(): ?string
    {
        return $this->nom_produit;
    }

    public function setNomProduit(string $nom_produit): self
    {
        $this->nom_produit = $nom_produit;

        return $this;
    }

    public function getNbPts(): ?int
    {
        return $this->nb_pts;
    }

    public function setNbPts(int $nb_pts): self
    {
        $this->nb_pts = $nb_pts;

        return $this;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->id_categorie;
    }
    
    public function setIdCategorie(?Categorie $id_categorie): self
    {
        $this->id_categorie = $id_categorie;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
    /**
     * 
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $image
     */
    public function setImageFile(File $image = null): void
    {
        $this->imageFile = $image;

        if ($image) {
            
            echo"image vide";
        }

    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function __toString():string
    {
        return $this->getIdProduit().' | '.$this->getNomProduit();
    }


    
}
