<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Scalar\String_;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */

    private $id_user;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\NotBlank()
     */
    private $nom_user;

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\NotBlank()
     */
    private $prenom_user;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\NotNull
     */
    private $tel_user;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email_user;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", length=255)
     * * @Assert\Length(
     *      min = 4,
     *
     *      minMessage = "Votre mot de passe doit contenir minimum {{ limit }} characteres"
     *
     * )
     */
    private $mdp_user;

    /**
     * @ORM\Column(type="json")
     */
    private $role=[];

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $pdp;


    public function getIdUser(): ?int
    {
        return $this->id_user;
    }

    public function setIdUser(int $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getNomUser(): ?string
    {
        return $this->nom_user;
    }

    public function setNomUser(string $nom_user): self
    {
        $this->nom_user = $nom_user;

        return $this;
    }

    public function getPrenomUser(): ?string
    {
        return $this->prenom_user;
    }

    public function setPrenomUser(string $prenom_user): self
    {
        $this->prenom_user = $prenom_user;

        return $this;
    }

    public function getTelUser(): ?int
    {
        return $this->tel_user;
    }

    public function setTelUser(int $tel_user): self
    {
        $this->tel_user = $tel_user;

        return $this;
    }

    public function getEmailUser(): ?string
    {
        return $this->email_user;
    }

    public function setEmailUser(string $email_user): self
    {
        $this->email_user = $email_user;

        return $this;
    }

    public function getMdpUser(): ?string
    {
        return $this->mdp_user;
    }

    public function setMdpUser(string $mdp_user): self
    {
        $this->mdp_user = $mdp_user;

        return $this;
    }

    public function getRole(): ?array
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPdp()
    {
        return $this->pdp;
    }

    public function setPdp($pdp)
    {
        $this->pdp = $pdp;

        return $this;
    }

    public function getRoles()
    {
        $role= $this->role;
        $role[] = 'ROLE_USER';

        return array_unique($role);
    }

    public function getPassword()
    {
        return $this->mdp_user;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function getUsername()
    {
        return $this->email_user;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function __toString():string
    {
        return $this->id_user.' | '.$this->nom_user.' '.$this->getPrenomUser();
    }


    protected $captchaCode;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }
}
