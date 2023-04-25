<?php

namespace App\Entity;

use App\Repository\RelationTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RelationTypeRepository::class)]
class RelationType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getRelationType', 'getRelationTypesDetails', 'relation:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getRelationType', 'getRelationTypesDetails', 'relation:read'])]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getRelationType', 'getRelationTypesDetails', 'relation:read'])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['getRelationType', 'getRelationTypesDetails', 'relation:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'relation_type', targetEntity: Relation::class, orphanRemoval: true)]
    #[Groups(['getRelationTypesDetails'])]
    private Collection $relations;

    #[ORM\ManyToMany(targetEntity: Ressource::class, mappedBy: 'relationType')]
    private Collection $ressources;

    #[ORM\OneToMany(mappedBy: 'relation�_type', targetEntity: Statistic::class)]
    private Collection $statistics;

    public static function create(string $label, string $name): self
    {
        $relationType = new self();
        $relationType->label = $label;
        $relationType->name = $name;

        return $relationType;
    }

    public function __construct()
    {
        $this->relations = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->ressources = new ArrayCollection();
        $this->statistics = new ArrayCollection();
    }

    #[Groups(['getRelationType', 'getRelationTypesDetails', 'relation:read'])]
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

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

    /**
     * @return Collection<int, Relation>
     */
    public function getRelations(): Collection
    {
        return $this->relations;
    }

    public function addRelation(Relation $relation): self
    {
        if (!$this->relations->contains($relation)) {
            $this->relations->add($relation);
            $relation->setRelationType($this);
        }

        return $this;
    }

    public function removeRelation(Relation $relation): self
    {
        if ($this->relations->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getRelationType() === $this) {
                $relation->setRelationType(null);
            }
        }

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
            $ressource->addRelationType($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            $ressource->removeRelationType($this);
        }

        return $this;
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

    /**
     * @return Collection<int, Statistic>
     */
    public function getStatistics(): Collection
    {
        return $this->statistics;
    }

    public function addStatistic(Statistic $statistic): self
    {
        if (!$this->statistics->contains($statistic)) {
            $this->statistics->add($statistic);
            $statistic->setRelation�Type($this);
        }

        return $this;
    }

    public function removeStatistic(Statistic $statistic): self
    {
        if ($this->statistics->removeElement($statistic)) {
            // set the owning side to null (unless already changed)
            if ($statistic->getRelation�Type() === $this) {
                $statistic->setRelation�Type(null);
            }
        }

        return $this;
    }
}
