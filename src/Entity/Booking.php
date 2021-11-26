<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()

     */
    private ?int $nombrePersonne;

    /**
     * @ORM\ManyToOne(targetEntity=Offer::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Offer $offre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $nomComplet;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private ?string $email;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 12,
     *      max = 16,
     *      minMessage = "Votre numero de téléphone doit etre au moins  {{ limit }} characteres" ,
     *      maxMessage = "Votre numero de téléphone doit etre au plus  {{ limit }} characteres"
     * )
     */
    private ?int $telephone;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombrePersonne(): ?int
    {
        return $this->nombrePersonne;
    }

    public function setNombrePersonne(int $nombrePersonne): self
    {
        $this->nombrePersonne = $nombrePersonne;

        return $this;
    }

    public function getOffre(): ?Offer
    {
        return $this->offre;
    }

    public function setOffre(?Offer $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getNomComplet(): ?string
    {
        return $this->nomComplet;
    }

    public function setNomComplet(string $nomComplet): self
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }
}
