<?php

namespace App\Entity;

use App\Repository\StatisticsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StatisticsRepository::class)]
class Statistic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getStats'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'statistics')]
    private ?RelationType $relation_type = null;

    #[ORM\ManyToOne(inversedBy: 'statistics')]
    #[Groups(['getStats'])]
    private ?RessourceType $ressource_type = null;

    #[ORM\Column]
    #[Groups(['getStats'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'statistics')]
    #[Groups(['getStats'])]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'statistics')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getStats'])]
    private ?StatisticType $type = null;

    public static function create($type, $relation_type, $resource_type, $category): self
    {
        $statistics = new self();
        $statistics->relation_type = $relation_type;
        $statistics->ressource_type = $resource_type;
        $statistics->category = $category;
        $statistics->type = $type;
        $statistics->createdAt = new \DateTimeImmutable();

        return $statistics;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?StatisticType
    {
        return $this->type;
    }

    public function setType(?StatisticType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
