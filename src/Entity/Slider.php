<?php

namespace App\Entity;

use App\Repository\SliderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SliderRepository::class)
 */
class Slider
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
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank()
     */
    private ?string $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $textBtn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $url;


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private ?string $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isDisplayed;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $isVideo = false;

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


    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getTextBtn(): ?string
    {
        return $this->textBtn;
    }

    public function setTextBtn(?string $textBtn): self
    {
        $this->textBtn = $textBtn;

        return $this;
    }


    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

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

    public function getIsDisplayed(): ?bool
    {
        return $this->isDisplayed;
    }

    public function setIsDisplayed(bool $isDisplayed): self
    {
        $this->isDisplayed = $isDisplayed;

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getIsVideo(): ?bool
    {
        return $this->isVideo;
    }

    public function setIsVideo(bool $isVideo): self
    {
        $this->isVideo = $isVideo;

        return $this;
    }
}
