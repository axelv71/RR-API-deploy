<?php

namespace App\Entity;

use App\Repository\SettingsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: SettingsRepository::class)]
class Settings
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getUsers', 'getSettings'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['getUsers', 'getSettings'])]
    private ?bool $isDark = null;

    #[ORM\Column]
    #[Groups(['getSettings'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToOne(inversedBy: 'settings', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    #[Groups(['getUsers', 'getSettings'])]
    private ?bool $allowNotifications = null;

    #[ORM\Column]
    #[Groups(['getUsers', 'getSettings'])]
    private ?bool $useDeviceMode = null;

    #[Groups(['getUsers', 'getSettings'])]
    #[ORM\ManyToOne(inversedBy: 'settings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Theme $theme = null;

    #[ORM\ManyToOne(inversedBy: 'settings')]
    #[Groups(['getUsers', 'getSettings'])]
    private ?Language $language = null;

    public static function create($isDark, $allowNotifications, $useDeviceMode, $language, $theme): self
    {
        $settings = new self();
        $settings->isDark = $isDark;
        $settings->allowNotifications = $allowNotifications;
        $settings->useDeviceMode = $useDeviceMode;
        $settings->language = $language;
        $settings->theme = $theme;

        return $settings;
    }

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isIsDark(): ?bool
    {
        return $this->isDark;
    }

    public function setIsDark(bool $isDark): self
    {
        $this->isDark = $isDark;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function isAllowNotifications(): ?bool
    {
        return $this->allowNotifications;
    }

    public function setAllowNotifications(bool $allowNotifications): self
    {
        $this->allowNotifications = $allowNotifications;

        return $this;
    }

    public function isUseDeviceMode(): ?bool
    {
        return $this->useDeviceMode;
    }

    public function setUseDeviceMode(bool $useDeviceMode): self
    {
        $this->useDeviceMode = $useDeviceMode;

        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getLanguage(): ?Language
    {
        return $this->language;
    }

    public function setLanguage(?Language $language): self
    {
        $this->language = $language;

        return $this;
    }
}
