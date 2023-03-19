<?php

namespace App\Entity;

use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
class Relation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getRelationTypesDetails", "relation:read"])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Receiver')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getRelationTypesDetails", "relation:read"])]
    private ?User $Sender = null;

    #[ORM\ManyToOne(inversedBy: "received_relation")]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["getRelationTypesDetails", "relation:read"])]
    private ?User $Receiver = null;

    #[ORM\Column]
    #[Groups(["getRelationTypesDetails", "relation:read"])]
    private ?bool $isAccepted = null;

    #[ORM\ManyToOne(inversedBy: 'relations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(["relation:read"])]
    private ?RelationType $relation_type = null;

    #[ORM\Column]
    #[Groups(["relation:read"])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(["relation:read"])]
    private ?\DateTimeImmutable $updatedAt = null;

    public static function create($Sender, $Receiver, $relation_type) : self
    {
        $relation = new self();
        $relation->Sender = $Sender;
        $relation->Receiver = $Receiver;
        $relation->relation_type = $relation_type;
        $relation->isAccepted = False;
        return $relation;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    //public  function __construct() { }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSender(): ?User
    {
        return $this->Sender;
    }

    public function setSender(?User $Sender): self
    {
        $this->Sender = $Sender;

        return $this;
    }

    public function getReceiver(): ?User
    {
        return $this->Receiver;
    }

    public function setReceiver(?User $Receiver): self
    {
        $this->Receiver = $Receiver;

        return $this;
    }

    public function isIsAccepted(): ?bool
    {
        return $this->isAccepted;
    }

    public function setIsAccepted(bool $isAccepted): self
    {
        $this->isAccepted = $isAccepted;

        return $this;
    }

    public function getRelationType(): ?RelationType
    {
        return $this->relation_type;
    }

    public function setRelationType(?RelationType $relation_type): self
    {
        $this->relation_type = $relation_type;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}
