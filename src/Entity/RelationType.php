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
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(['getRelationType', 'getRelationTypesDetails', 'relation:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'relation_type', targetEntity: Relation::class, orphanRemoval: true)]
    #[Groups(['getRelationTypesDetails'])]
    private Collection $relations;

    public function __construct()
    {
        $this->relations = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
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
}
