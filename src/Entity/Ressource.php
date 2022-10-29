<?php

namespace App\Entity;

use App\Repository\RessourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RessourceRepository::class)]
class Ressource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getRessources","getMedia"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(["getRessources","getMedia"])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["getRessources","getMedia"])]
    private ?bool $isValid = null;

    #[ORM\Column]
    #[Groups(["getRessources","getMedia"])]
    private ?bool $isPublished = null;

    #[ORM\Column]
    #[Groups(["getRessources","getMedia"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getRessources","getMedia"])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'ressources')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getRessources","getMedia"])]
    private ?User $creator = null;

    #[ORM\OneToMany(mappedBy: 'ressource', targetEntity: Comment::class)]
    #[Groups(["getRessources"])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'ressource', targetEntity: Media::class)]
    #[Groups(["getRessources"])]
    private Collection $media;

    #[ORM\OneToMany(mappedBy: 'ressource_like', targetEntity: Like::class)]
    private Collection $likes;

    #[ORM\OneToMany(mappedBy: 'ressource_favorite', targetEntity: Favorite::class)]
    private Collection $favorites;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->media = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
