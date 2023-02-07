<?php

namespace App\Entity;

use App\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getRoles"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getRoles"])]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups(["getRoles"])]
    private ?\DateTimeImmutable $createdAt = null;

    // TODO: Corriger le bug de groupe sur le User
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: User::class)]
    private Collection $users;


    public function __construct ()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->name = "NOT_CONNECTED_USER";
        $this->users = new ArrayCollection();
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
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUsers(User $users): self
    {
        if (!$this->users->contains($users)) {
            $this->users->add($users);
            $users->setUserRole($this);
        }

        return $this;
    }

    public function removeUsers(User $users): self
    {
        if ($this->users->removeElement($users)) {
            // set the owning side to null (unless already changed)
            if ($users->getUserRole() === $this) {
                $users->setUserRole(null);
            }
        }

        return $this;
    }
}
