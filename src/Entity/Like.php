<?php

namespace App\Entity;

use App\Repository\LikeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LikeRepository::class)]
#[ORM\Table(name: '`like`')]
class Like
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getLikes"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[Groups(["getLikes", "createLike"])]
    private ?User $user_like = null;

    #[ORM\ManyToOne(inversedBy: 'likes')]
    #[Groups(["getLikes", "createLike"])]
    private ?Ressource $ressource_like = null;

    #[ORM\Column]
    private ?bool $isLiked = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserLike(): ?User
    {
        return $this->user_like;
    }

    public function setUserLike(?User $user_like): self
    {
        $this->user_like = $user_like;

        return $this;
    }

    public function getRessourceLike(): ?Ressource
    {
        return $this->ressource_like;
    }

    public function setRessourceLike(?Ressource $ressource_like): self
    {
        $this->ressource_like = $ressource_like;

        return $this;
    }

    public function isIsLiked(): ?bool
    {
        return $this->isLiked;
    }

    public function setIsLiked(bool $isLiked): self
    {
        $this->isLiked = $isLiked;

        return $this;
    }
}
