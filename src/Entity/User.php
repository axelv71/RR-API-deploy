<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getUsers", "getRessources","getRoles", "getComments", "getRelationTypesDetails"])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(["getUsers","getRessources","getRoles", "getRelationTypesDetails"])]
    private ?string $email = null;


    #[ORM\Column]
    #[Groups(["getUsers"])]
    private array $roles = [];


    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(["getUsers"])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUsers", "getRelationTypesDetails"])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUsers", "getRelationTypesDetails"])]
    private ?string $surname = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getUsers","getRessources","getRoles", "getRelationTypesDetails"])]
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

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\Column]
    #[Groups(["getUsers"])]
    private ?bool $isActive = null;

    #[ORM\OneToMany(mappedBy: 'Sender', targetEntity: Relation::class, orphanRemoval: true)]
    private Collection $sent_relation;

    #[ORM\OneToMany(mappedBy: 'Receiver', targetEntity: Relation::class, orphanRemoval: true)]
    private Collection $received_relation;

    #[ORM\OneToMany(mappedBy: 'user_like', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'user_favorite', targetEntity: Favorite::class, orphanRemoval: true)]
    private Collection $favorites;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Role $user_role = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->ressources = new ArrayCollection();
        $this->comments = new ArrayCollection();

        //Relations
        $this->sent_relation = new ArrayCollection();
        $this->received_relation = new ArrayCollection();

        $this->likes = new ArrayCollection();
        $this->favorites = new ArrayCollection();
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
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;
        //$this->role_name = $this->roles->getName();

        return $this;
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

    /**
     * @return Collection<int, Relation>
     */
    public function getReceiver(): Collection
    {
        return $this->sent_relation;
    }

    public function addReceiver(Relation $receiver): self
    {
        if (!$this->sent_relation->contains($receiver)) {
            $this->sent_relation->add($receiver);
            $receiver->setSender($this);
        }

        return $this;
    }

    public function removeReceiver(Relation $receiver): self
    {
        if ($this->sent_relation->removeElement($receiver)) {
            // set the owning side to null (unless already changed)
            if ($receiver->getSender() === $this) {
                $receiver->setSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getReceivedRelation(): Collection
    {
        return $this->received_relation;
    }

    public function addReceivedRelation(Relation $receivedRelation): self
    {
        if (!$this->received_relation->contains($receivedRelation)) {
            $this->received_relation->add($receivedRelation);
            $receivedRelation->setReceiver($this);
        }

        return $this;
    }

    public function removeReceivedRelation(Relation $receivedRelation): self
    {
        if ($this->received_relation->removeElement($receivedRelation)) {
            // set the owning side to null (unless already changed)
            if ($receivedRelation->getReceiver() === $this) {
                $receivedRelation->setReceiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setUserLike($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUserLike() === $this) {
                $like->setUserLike(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setUserFavorite($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getUserFavorite() === $this) {
                $favorite->setUserFavorite(null);
            }
        }

        return $this;
    }

    public function getUserRole(): ?Role
    {
        return $this->user_role;
    }

    public function setUserRole(?Role $user_role): self
    {
        $this->user_role = $user_role;

        return $this;
    }
}
