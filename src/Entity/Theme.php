<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['getThemes', 'getSettings', 'getUsers'])]
    private ?int $id = null;

    #[Groups(['getThemes', 'getSettings', 'getUsers'])]
    #[ORM\Column(length: 255)]
    private ?string $label = null;
    #[Groups(['getThemes', 'getSettings', 'getUsers'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Groups(['getThemes', 'getSettings', 'getUsers'])]
    #[ORM\Column(length: 255)]
    private ?string $primary_color = null;

    #[Groups(['getThemes', 'getSettings', 'getUsers'])]
    #[ORM\Column(length: 255)]
    private ?string $secondary_color = null;

    #[ORM\OneToMany(mappedBy: 'theme', targetEntity: Settings::class, orphanRemoval: true)]
    private Collection $settings;

    public static function create(string $label, string $name, string $primary_color, string $secondary_color): self
    {
        $theme = new self();
        $theme->label = $label;
        $theme->name = $name;
        $theme->primary_color = $primary_color;
        $theme->secondary_color = $secondary_color;

        return $theme;
    }

    public function __construct()
    {
        $this->settings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrimaryColor(): ?string
    {
        return $this->primary_color;
    }

    public function setPrimaryColor(string $primary_color): self
    {
        $this->primary_color = $primary_color;

        return $this;
    }

    public function getSecondaryColor(): ?string
    {
        return $this->secondary_color;
    }

    public function setSecondaryColor(string $secondary_color): self
    {
        $this->secondary_color = $secondary_color;

        return $this;
    }

    /**
     * @return Collection<int, Settings>
     */
    public function getSettings(): Collection
    {
        return $this->settings;
    }

    public function addSetting(Settings $setting): self
    {
        if (!$this->settings->contains($setting)) {
            $this->settings->add($setting);
            $setting->setTheme($this);
        }

        return $this;
    }

    public function removeSetting(Settings $setting): self
    {
        if ($this->settings->removeElement($setting)) {
            // set the owning side to null (unless already changed)
            if ($setting->getTheme() === $this) {
                $setting->setTheme(null);
            }
        }

        return $this;
    }
}
