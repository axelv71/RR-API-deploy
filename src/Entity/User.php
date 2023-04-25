<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getUsers', 'getRessources', 'getRoles', 'getComments', 'getRelationTypesDetails',
        'getFavorites', 'createFavorite', 'getLikes', 'createLike', 'userLogin', 'relation:read', 'getNotifications'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['getUsers', 'getRessources', 'getComments', 'getRoles', 'getRelationTypesDetails', 'userLogin', 'relation:read'])]
    private ?string $email = null;

    #[OA\Property(type: 'string', enum: ['ROLE_USER', 'ROLE_ADMIN'])]
    #[ORM\Column]
    #[Groups(['getUsers', 'userLogin'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getUsers', 'getRessources', 'getComments', 'getRelationTypesDetails','getLikes', 'getFavorites', 'userLogin', 'relation:read'])]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getUsers', 'getRessources', 'getComments', 'getRelationTypesDetails','getLikes', 'getFavorites', 'userLogin', 'relation:read'])]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getUsers', 'getRessources', 'getComments', 'getRoles', 'getRelationTypesDetails', 'getFavorites', 'getLikes', 'userLogin', 'relation:read', 'getNotifications'])]
    private ?string $account_name = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['getUsers'])]
    private ?\DateTimeImmutable $birthday = null;

    #[ORM\Column]
    #[Groups(['getUsers'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Settings::class, cascade: ['persist', 'remove'])]
    #[Groups(['getUsers'])]
    private ?Settings $settings = null;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Ressource::class, orphanRemoval: true)]
    private Collection $ressources;

    #[ORM\OneToMany(mappedBy: 'creator', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\Column]
    #[Groups(['getUsers', 'userLogin'])]
    private ?bool $isActive = null;

    #[ORM\OneToMany(mappedBy: 'Sender', targetEntity: Relation::class, orphanRemoval: true)]
    private Collection $sent_relation;

    #[ORM\OneToMany(mappedBy: 'Receiver', targetEntity: Relation::class, orphanRemoval: true)]
    private Collection $received_relation;

    #[ORM\OneToMany(mappedBy: 'user_like', targetEntity: Like::class, orphanRemoval: true)]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'user_favorite', targetEntity: Favorite::class, orphanRemoval: true)]
    private Collection $favorites;

    #[ORM\Column(type: 'boolean')]
    #[Groups(['getUsers', 'userLogin'])]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'receiver', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    #[ORM\OneToMany(mappedBy: 'citizen', targetEntity: ExploitedRessource::class)]
    private Collection $exploitedRessources;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->ressources = new ArrayCollection();
        $this->comments = new ArrayCollection();

        // Relations
        $this->sent_relation = new ArrayCollection();
        $this->received_relation = new ArrayCollection();

        $this->likes = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->exploitedRessources = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getAccountName();
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
     *  Username method for the security component.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return $this->getUserIdentifier();
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $name): self
    {
        $this->first_name = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $surname): self
    {
        $this->last_name = $surname;

        return $this;
    }

    public function getAccountName(): ?string
    {
        return $this->account_name;
    }

    public function setAccountName(string $pseudo): self
    {
        $this->account_name = $pseudo;

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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): self
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setReceiver($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getReceiver() === $this) {
                $notification->setReceiver(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ExploitedRessource>
     */
    public function getExploitedRessources(): Collection
    {
        return $this->exploitedRessources;
    }

    public function addExploitedRessource(ExploitedRessource $exploitedRessource): self
    {
        if (!$this->exploitedRessources->contains($exploitedRessource)) {
            $this->exploitedRessources->add($exploitedRessource);
            $exploitedRessource->setCitizen($this);
        }

        return $this;
    }

    public function removeExploitedRessource(ExploitedRessource $exploitedRessource): self
    {
        if ($this->exploitedRessources->removeElement($exploitedRessource)) {
            // set the owning side to null (unless already changed)
            if ($exploitedRessource->getCitizen() === $this) {
                $exploitedRessource->setCitizen(null);
            }
        }

        return $this;
    }
}
