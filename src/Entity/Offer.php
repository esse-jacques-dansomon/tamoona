<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OfferRepository::class)
 * @UniqueEntity(fields={"title"}, message="Ce title exite déja")
 * @UniqueEntity(fields={"slug"}, message="Ce slug exite déja")
 */

class Offer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $image;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $destination;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $delaiAt;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private ?float $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $badgeTexte;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isDisplayed;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $services;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $programme;

    /**
     * @ORM\Column(type="integer",  nullable=true)
     */
    private $nombreDeJour;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nombreMaxPersonne;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Booking::class, mappedBy="offre")
     */
    private $bookings;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=OfferProgramme::class, mappedBy="offer")
     */
    private $programmes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $shortDescription;

    /**
     * Offer constructor.
     * @param bool|null $isDisplayed
     */
    public function __construct()
    {
        $this->isDisplayed = true;
        $this->bookings = new ArrayCollection();
        $this->programmes = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): self
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDelaiAt(): ?\DateTimeInterface
    {
        return $this->delaiAt;
    }

    public function setDelaiAt(\DateTimeInterface $delaiAt): self
    {
        $this->delaiAt = $delaiAt;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBadgeTexte(): ?string
    {
        return $this->badgeTexte;
    }

    public function setBadgeTexte(string $badgeTexte): self
    {
        $this->badgeTexte = $badgeTexte;

        return $this;
    }

    public function getIsDisplayed(): ?bool
    {
        return $this->isDisplayed;
    }

    public function setIsDisplayed(bool $isDisplayed): self
    {
        $this->isDisplayed = $isDisplayed;

        return $this;
    }

    public function getServices(): ?string
    {
        return $this->services;
    }

    public function setServices(string $services): self
    {
        $this->services = $services;

        return $this;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(string $programme): self
    {
        $this->programme = $programme;

        return $this;
    }

    public function getNombreDeJour(): ?int
    {
        return $this->nombreDeJour;
    }

    public function setNombreDeJour(int $nombreDeJour): self
    {
        $this->nombreDeJour = $nombreDeJour;

        return $this;
    }

    public function getNombreMaxPersonne(): ?int
    {
        return $this->nombreMaxPersonne;
    }

    public function setNombreMaxPersonne(?int $nombreMaxPersonne): self
    {
        $this->nombreMaxPersonne = $nombreMaxPersonne;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Booking[]
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->setOffre($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getOffre() === $this) {
                $booking->setOffre(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->title;
    }

    /**
     * @return array|Collection|mixed[]
     */
    public function getProgrammes()
    {
        return $this->programmes;

    }

    public function addProgramme(OfferProgramme $programme): self
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes[] = $programme;
            $programme->setOffer($this);
        }

        return $this;
    }

    public function removeProgramme(OfferProgramme $programme): self
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getOffer() === $this) {
                $programme->setOffer(null);
            }
        }

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }


}
