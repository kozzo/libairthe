<?php

namespace App\Entity;

use App\Repository\DestinationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DestinationRepository::class)]
class Destination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    /**
     * @var Collection<int, Travel>
     */
    #[ORM\OneToMany(targetEntity: Travel::class, mappedBy: 'destination')]
    private Collection $travels;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $path = null;

    public function __construct()
    {
        $this->travels = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->label . ' - ' . $this->country;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, Travel>
     */
    public function getTravels(): Collection
    {
        return $this->travels;
    }

    public function addTravel(Travel $travel): static
    {
        if (!$this->travels->contains($travel)) {
            $this->travels->add($travel);
            $travel->setDestination($this);
        }

        return $this;
    }

    public function removeTravel(Travel $travel): static
    {
        if ($this->travels->removeElement($travel)) {
            // set the owning side to null (unless already changed)
            if ($travel->getDestination() === $this) {
                $travel->setDestination(null);
            }
        }

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): static
    {
        $this->path = $path;

        return $this;
    }
}
