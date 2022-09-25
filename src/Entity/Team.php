<?php

namespace App\Entity;

use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamRepository::class)
 */
class Team
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $poste;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $favoriteDestination;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $travelStory;

    /**
     * @ORM\OneToMany(targetEntity=TeamImage::class, mappedBy="team")
     */
    private $teamImages;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isCitation;

    public function __construct()
    {
        $this->teamImages = new ArrayCollection();
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

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

        return $this;
    }

    public function getFavoriteDestination(): ?string
    {
        return $this->favoriteDestination;
    }

    public function setFavoriteDestination(?string $favoriteDestination): self
    {
        $this->favoriteDestination = $favoriteDestination;

        return $this;
    }

    public function getTravelStory(): ?string
    {
        return $this->travelStory;
    }

    public function setTravelStory(?string $travelStory): self
    {
        $this->travelStory = $travelStory;

        return $this;
    }

    /**
     * @return Collection<int, TeamImage>
     */
    public function getTeamImages(): Collection
    {
        return $this->teamImages;
    }

    public function addTeamImage(TeamImage $teamImage): self
    {
        if (!$this->teamImages->contains($teamImage)) {
            $this->teamImages[] = $teamImage;
            $teamImage->setTeam($this);
        }

        return $this;
    }

    public function removeTeamImage(TeamImage $teamImage): self
    {
        if ($this->teamImages->removeElement($teamImage)) {
            // set the owning side to null (unless already changed)
            if ($teamImage->getTeam() === $this) {
                $teamImage->setTeam(null);
            }
        }

        return $this;
    }

   //to string
    public function __toString()
    {
        return $this->name;
    }

    public function getIsCitation(): ?bool
    {
        return $this->isCitation;
    }

    public function setIsCitation(bool $isCitation): self
    {
        $this->isCitation = $isCitation;

        return $this;
    }
}


