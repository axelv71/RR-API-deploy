<?php

namespace App\Entity;

use App\Repository\StatisticsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatisticsRepository::class)]
class Statistics
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'statistics')]
    private ?RelationType $relation_type = null;

    #[ORM\ManyToOne(inversedBy: 'statistics')]
    private ?RessourceType $ressource_type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'statistics')]
    private ?Category $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getRelation�Type(): ?RelationType
    {
        return $this->relation_type;
    }

    public function setRelation�Type(?RelationType $relation�_type): self
    {
        $this->relation_type = $relation�_type;

        return $this;
    }

    public function getRessourceType(): ?RessourceType
    {
        return $this->ressource_type;
    }

    public function setRessourceType(?RessourceType $ressource_type): self
    {
        $this->ressource_type = $ressource_type;

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
}
