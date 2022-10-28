<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getUsers", "getRessources","getRoles"])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(["getUsers","getRessources","getRoles"])]
    private ?string $email = null;


    #[ORM\ManyToOne(inversedBy: 'role')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $roles = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUsers","getRessources"])]
    private ?string $role_name = null;
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(["getUsers"])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUsers"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUsers"])]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUsers","getRessources","getRoles"])]
    private ?string $pseudo = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["getUsers"])]
    private ?\DateTimeImmutable $birthday = null;

    #[ORM\Column]
    #[Groups(["getUsers"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Settings::class ,cascade: ['persist', 'remove'])]
    #[Groups(["getUsers"])]
    private ?Settings $settings = null;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Ressource::class, orphanRemoval: true)]
    private Collection $ressources;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\Column]
    #[Groups(["getUsers"])]
    private ?bool $isActive = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->ressources = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->roles = new Role();
        $this->role_name = $this->roles->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): Role
    {
        return $this->roles;
    }

    public function setRoles(Role $roles): self
    {
        $this->roles = $roles;
        $this->role_name = $this->roles->getName();

        return $this;
    }

    public function getRoleName(): string
    {
        return $this->role_name;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getBirthday(): ?\DateTimeImmutable
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeImmutable $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getSettings(): ?Settings
    {
        return $this->settings;
    }

    public function setSettings(Settings $settings): self
    {
        // set the owning side of the relation if necessary
        if ($settings->getUser() !== $this) {
            $settings->setUser($this);
        }

        $this->settings = $settings;

        return $this;
    }

    /**
     * @return Collection<int, Ressource>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
            $ressource->setCreator($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getCreator() === $this) {
                $ressource->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setCreator($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getCreator() === $this) {
                $comment->setCreator(null);
            }
        }

        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }
}
