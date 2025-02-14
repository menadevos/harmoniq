<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class EventSelection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private string $eventType;

    #[ORM\Column(type: 'string', length: 50)]
    private string $eventTime;

    #[ORM\Column(type: 'date')]
    private \DateTimeInterface $eventDate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $foodType = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $selectedRestaurant = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $decorType = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $decorExtras = null; // Extra decorations

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $musicType = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $musicExtras = null; // Extra music options

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $photographyType = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $photographyExtras = null; // Extra photography options

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $venueType = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $venueExtras = null; // Extra venue options

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $menuSelection = null;

    // Add a new property for comments
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $categoryComments = null;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function setEventType(string $eventType): self
    {
        $this->eventType = $eventType;
        return $this;
    }

    public function getEventTime(): string
    {
        return $this->eventTime;
    }

    public function setEventTime(string $eventTime): self
    {
        $this->eventTime = $eventTime;
        return $this;
    }

    public function getEventDate(): \DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): self
    {
        $this->eventDate = $eventDate;
        return $this;
    }

    public function getFoodType(): ?string
    {
        return $this->foodType;
    }

    public function setFoodType(?string $foodType): self
    {
        $this->foodType = $foodType;
        return $this;
    }

    public function getSelectedRestaurant(): ?string
    {
        return $this->selectedRestaurant;
    }

    public function setSelectedRestaurant(?string $selectedRestaurant): self
    {
        $this->selectedRestaurant = $selectedRestaurant;
        return $this;
    }

    public function getDecorType(): ?string
    {
        return $this->decorType;
    }

    public function setDecorType(?string $decorType): self
    {
        $this->decorType = $decorType;
        return $this;
    }

    public function getDecorExtras(): ?array
    {
        return $this->decorExtras ? json_decode($this->decorExtras, true) : null;
    }

    public function setDecorExtras(?array $decorExtras): self
    {
        $this->decorExtras = $decorExtras ? json_encode($decorExtras) : null;
        return $this;
    }

    public function getMusicType(): ?string
    {
        return $this->musicType;
    }

    public function setMusicType(?string $musicType): self
    {
        $this->musicType = $musicType;
        return $this;
    }

    public function getMusicExtras(): ?array
    {
        return $this->musicExtras ? json_decode($this->musicExtras, true) : null;
    }

    public function setMusicExtras(?array $musicExtras): self
    {
        $this->musicExtras = $musicExtras ? json_encode($musicExtras) : null;
        return $this;
    }

    public function getPhotographyType(): ?string
    {
        return $this->photographyType;
    }

    public function setPhotographyType(?string $photographyType): self
    {
        $this->photographyType = $photographyType;
        return $this;
    }

    public function getPhotographyExtras(): ?array
    {
        return $this->photographyExtras ? json_decode($this->photographyExtras, true) : null;
    }

    public function setPhotographyExtras(?array $photographyExtras): self
    {
        $this->photographyExtras = $photographyExtras ? json_encode($photographyExtras) : null;
        return $this;
    }

    public function getVenueType(): ?string
    {
        return $this->venueType;
    }

    public function setVenueType(?string $venueType): self
    {
        $this->venueType = $venueType;
        return $this;
    }

    public function getVenueExtras(): ?array
    {
        return $this->venueExtras ? json_decode($this->venueExtras, true) : null;
    }

    public function setVenueExtras(?array $venueExtras): self
    {
        $this->venueExtras = $venueExtras ? json_encode($venueExtras) : null;
        return $this;
    }

    public function getCategoryComments(): ?array
    {
        return $this->categoryComments;
    }

    public function setCategoryComments(?array $categoryComments): self
    {
        $this->categoryComments = $categoryComments;
        return $this;
    }

    public function getMenuSelection(): ?array
    {
        return json_decode($this->menuSelection, true);
    }

    public function setMenuSelection(?array $menuSelection): self
    {
        $this->menuSelection = $menuSelection ? json_encode($menuSelection) : null;
        return $this;
    }
    #[ORM\Column(type: 'boolean', name: 'is_paid')]
    private bool $isPaid = false;
    

    public function getIsPaid(): bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;
        return $this;
    }
    
    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $totalPrice = null;

    // ... autres méthodes

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }
}




