<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getRessources', 'getMedia', 'getComments', 'getFavorites', 'createFavorite', 'getLikes', 'createLike', 'getNotifications'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getRessources', 'getMedia', 'getFavorites', 'getLikes', 'getNotifications'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['getRessources', 'getMedia', 'getFavorites', 'getLikes', 'getNotifications'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['getRessources', 'getMedia', 'getFavorites', 'getLikes'])]
    private ?bool $isValid = null;

    #[ORM\Column]
    #[Groups(['getRessources', 'getMedia', 'getFavorites', 'getLikes'])]
    private ?bool $isPublished = null;

    #[ORM\Column]
    #[Groups(['getRessources', 'getMedia', 'getFavorites', 'getLikes'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getRessources', 'getMedia', 'getFavorites', 'getLikes'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getRessources', 'getMedia', 'getFavorites', 'getLikes'])]
    private ?User $creator = null;

    #[ORM\OneToMany(mappedBy: 'ressource', targetEntity: Comment::class, orphanRemoval: true)]
    #[Groups(['getRessources'])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'ressource', targetEntity: Media::class, orphanRemoval: true)]
    #[Groups(['getRessources', 'getLikes', 'getFavorites'])]
    private Collection $media;

    #[ORM\OneToMany(mappedBy: 'ressource_like', targetEntity: Like::class, orphanRemoval: true)]
    #[Groups(['getRessources'])]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'ressource_favorite', targetEntity: Favorite::class, orphanRemoval: true)]
    #[Groups(['getRessources'])]
    private Collection $favorites;

    #[ORM\ManyToMany(targetEntity: RelationType::class, inversedBy: 'ressources', fetch: 'EAGER')]
    private Collection $relationType;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: Notification::class, orphanRemoval: true)]
    private Collection $notifications;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    private ?RessourceType $type = null;

    #[ORM\OneToMany(mappedBy: 'ressource', targetEntity: ExploitedRessource::class)]
    private Collection $exploitedRessources;

    #[Groups(['getRessources'])]
    private bool $isFavorite = false;
    #[Groups(['getRessources'])]
    private bool $isLiked = false;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->favorites = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->relationType = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->exploitedRessources = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function isIsValid(): ?bool
    {
        return $this->isValid;
    }

    public function setIsValid(?bool $isValid): self
    {
        $this->isValid = $isValid;

        return $this;
    }

    public function isIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

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
            $comment->setRessource($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getRessource() === $this) {
                $comment->setRessource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): self
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setRessource($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): self
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getRessource() === $this) {
                $medium->setRessource(null);
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
            $like->setRessourceLike($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getRessourceLike() === $this) {
                $like->setRessourceLike(null);
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
            $favorite->setRessourceFavorite($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getRessourceFavorite() === $this) {
                $favorite->setRessourceFavorite(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RelationType>
     */
    public function getRelationType(): Collection
    {
        return $this->relationType;
    }

    public function addRelationType(RelationType $relationType): self
    {
        if (!$this->relationType->contains($relationType)) {
            $this->relationType->add($relationType);
        }

        return $this;
    }

    public function removeRelationType(RelationType $relationType): self
    {
        $this->relationType->removeElement($relationType);

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
            $notification->setResource($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): self
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getResource() === $this) {
                $notification->setResource(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getType(): ?RessourceType
    {
        return $this->type;
    }

    public function setType(?RessourceType $type): self
    {
        $this->type = $type;

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
            $exploitedRessource->setRessource($this);
        }

        return $this;
    }

    public function removeExploitedRessource(ExploitedRessource $exploitedRessource): self
    {
        if ($this->exploitedRessources->removeElement($exploitedRessource)) {
            // set the owning side to null (unless already changed)
            if ($exploitedRessource->getRessource() === $this) {
                $exploitedRessource->setRessource(null);
            }
        }

        return $this;
    }

    public function setIsFavorite(bool $isFavorite): self
    {
        $this->isFavorite = $isFavorite;

        return $this;
    }

    public function getIsFavorite(): ?bool
    {
        return $this->isFavorite;
    }

    public function setIsLiked(bool $isLiked): self
    {
        $this->isLiked = $isLiked;

        return $this;
    }

    public function getIsLiked(): ?bool
    {
        return $this->isLiked;
    }
}
