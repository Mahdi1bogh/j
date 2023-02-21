<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 * @Vich\Uploadable
 */
class Categorie
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     */
    private $id_categorie;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="s il vous plaît choisir un nom ")
     * 
     */
    private $nom_categorie;

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
     * @ORM\OneToMany(targetEntity=Produits::class, mappedBy="id_categorie")
     * 
     */
    private $produit;

    public function __construct()
    {
        $this->produit = new ArrayCollection();
    }


    public function getIdCategorie(): ?int
    {
        return $this->id_categorie;
    }

    public function setIdCategorie(int $id_categorie): self
    {
        $this->id_categorie = $id_categorie;

        return $this;
    }

    public function getNomCategorie(): ?string
    {
        return $this->nom_categorie;
    }

    public function setNomCategorie(string $nom_categorie): self
    {
        $this->nom_categorie = $nom_categorie;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string$image): self
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
    
    /**
     * @return Collection<int, Produits>
     */
    public function getProduit(): Collection
    {
        return $this->produit;
    }

    public function addProduit(Produits $produit): self
    {
        if (!$this->produit->contains($produit)) {
            $this->produit[] = $produit;
            $produit->setIdCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produit->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getIdCategorie() === $this) {
                $produit->setIdCategorie(null);
            }
        }

        return $this;
    }

    public function __toString() 
{
    return (string) $this->nom_categorie; 
}

}
