<?php

namespace App\Entity;

use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getNotifications'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $receiver = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['getNotifications'])]
    private ?User $sender = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[Groups(['getNotifications'])]
    private ?NotificationType $notificationType = null;

    #[ORM\Column(length: 255)]
    #[Groups(['getNotifications'])]
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['getNotifications'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    #[Groups(['getNotifications'])]
    private ?Ressource $resource = null;



    public static function create($sender, User $receiver, NotificationType $type, string $content, Ressource $resource=null): self
    {
        $notification = new self();
        $notification->sender = $sender;
        $notification->receiver = $receiver;
        $notification->notificationType = $type;
        $notification->content = $content;
        $notification->resource = $resource;

        return $notification;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getResource(): ?Ressource
    {
        return $this->resource;
    }

    public function setResource(?Ressource $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getNotificationType(): ?NotificationType
    {
        return $this->notificationType;
    }

    public function setNotificationType(?NotificationType $notificationType): self
    {
        $this->notificationType = $notificationType;

        return $this;
    }
}
