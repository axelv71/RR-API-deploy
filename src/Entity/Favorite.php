<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
class Favorite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    private ?User $user_favorite = null;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    private ?Ressource $ressource_favorite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserFavorite(): ?User
    {
        return $this->user_favorite;
    }

    public function setUserFavorite(?User $user_favorite): self
    {
        $this->user_favorite = $user_favorite;

        return $this;
    }

    public function getRessourceFavorite(): ?Ressource
    {
        return $this->ressource_favorite;
    }

    public function setRessourceFavorite(?Ressource $ressource_favorite): self
    {
        $this->ressource_favorite = $ressource_favorite;

        return $this;
    }
}
