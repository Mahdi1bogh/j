<?php

namespace App\Entity;

use App\Repository\BlogRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=BlogRepository::class)
 */
class Blog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */

    private $id_blog;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email_publisher;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre_blog;

    /**
     * @ORM\Column(type="text", length=65535)
     */
    private $contenu_blog;

    /**
     * @ORM\Column(type="datetime")
     *  @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *  @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image_blog;


    public function getIdBlog(): ?int
    {
        return $this->id_blog;
    }

    public function setIdBlog(int $id_blog): self
    {
        $this->id_blog = $id_blog;

        return $this;
    }

    public function getEmailPublisher(): ?string
    {
        return $this->email_publisher;
    }

    public function setEmailPublisher(string $email_publisher): self
    {
        $this->email_publisher = $email_publisher;

        return $this;
    }

    public function getTitreBlog(): ?string
    {
        return $this->titre_blog;
    }

    public function setTitreBlog(string $titre_blog): self
    {
        $this->titre_blog = $titre_blog;

        return $this;
    }

    public function getContenuBlog(): ?string
    {
        return $this->contenu_blog;
    }

    public function setContenuBlog(string $contenu_blog): self
    {
        $this->contenu_blog = $contenu_blog;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getImageBlog(): ?string
    {
        return $this->image_blog;
    }

    public function setImageBlog(string $image_blog): self
    {
        $this->image_blog = $image_blog;

        return $this;
    }
}
