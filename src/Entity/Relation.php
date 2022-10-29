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
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'Receiver')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Sender = null;

    #[ORM\ManyToOne(inversedBy: 'received_relation')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Receiver = null;

    #[ORM\Column]
    private ?bool $isAccepted = null;

    #[ORM\ManyToOne(inversedBy: 'relations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RelationType $relation_type = null;

    public function __construct(User $Sender, User $Receiver, RelationType $relation_type)
    {
        $this->Sender = $Sender;
        $this->Receiver = $Receiver;
        $this->isAccepted = False;
        $this->relation_type = $relation_type;
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
}
