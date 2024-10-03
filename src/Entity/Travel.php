<?php

namespace App\Entity;

use App\Repository\TravelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TravelRepository::class)]
class Travel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column]
    private ?float $adultUnitPrice = null;

    #[ORM\Column]
    private ?float $childUnitPrice = null;

    #[ORM\Column]
    private ?int $totalSeats = null;

    #[ORM\Column]
    private ?int $availableSeats = null;

    #[ORM\Column(length: 255)]
    private ?string $note = null;

    #[ORM\Column]
    private ?int $dailySeats = null;

    #[ORM\Column(length: 255)]
    private ?string $departurePlace = null;

    #[ORM\Column(length: 255)]
    private ?string $arrivingPlace = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $deletedAt = null;

    #[ORM\ManyToOne(inversedBy: 'travels')]
    #[ORM\JoinColumn(nullable: false)]
    private ?destination $destination = null;

    /**
     * @var Collection<int, Review>
     */
    #[ORM\OneToMany(targetEntity: Review::class, mappedBy: 'travel')]
    private Collection $reviews;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'travel')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
        $this->reservations = new ArrayCollection();
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

    public function getAdultUnitPrice(): ?float
    {
        return $this->adultUnitPrice;
    }

    public function setAdultUnitPrice(float $adultUnitPrice): static
    {
        $this->adultUnitPrice = $adultUnitPrice;

        return $this;
    }

    public function getChildUnitPrice(): ?float
    {
        return $this->childUnitPrice;
    }

    public function setChildUnitPrice(float $childUnitPrice): static
    {
        $this->childUnitPrice = $childUnitPrice;

        return $this;
    }

    public function getTotalSeats(): ?int
    {
        return $this->totalSeats;
    }

    public function setTotalSeats(int $totalSeats): static
    {
        $this->totalSeats = $totalSeats;

        return $this;
    }

    public function getAvailableSeats(): ?int
    {
        return $this->availableSeats;
    }

    public function setAvailableSeats(int $availableSeats): static
    {
        $this->availableSeats = $availableSeats;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(string $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getDailySeats(): ?int
    {
        return $this->dailySeats;
    }

    public function setDailySeats(int $dailySeats): static
    {
        $this->dailySeats = $dailySeats;

        return $this;
    }

    public function getDeparturePlace(): ?string
    {
        return $this->departurePlace;
    }

    public function setDeparturePlace(string $departurePlace): static
    {
        $this->departurePlace = $departurePlace;

        return $this;
    }

    public function getArrivingPlace(): ?string
    {
        return $this->arrivingPlace;
    }

    public function setArrivingPlace(string $arrivingPlace): static
    {
        $this->arrivingPlace = $arrivingPlace;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getDestination(): ?destination
    {
        return $this->destination;
    }

    public function setDestination(?destination $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): static
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setTravel($this);
        }

        return $this;
    }

    public function removeReview(Review $review): static
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getTravel() === $this) {
                $review->setTravel(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setTravel($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getTravel() === $this) {
                $reservation->setTravel(null);
            }
        }

        return $this;
    }
}
