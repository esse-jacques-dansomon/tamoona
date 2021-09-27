<?php

namespace App\Entity;

use App\Repository\SliderRepository;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private ?string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $subTitle;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $context;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $textBtn1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $textBtn2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $url1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $url2;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $image;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isDisplayed;

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

    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    public function setSubTitle(?string $subTitle): self
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    public function getContext(): ?string
    {
        return $this->context;
    }

    public function setContext(?string $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getTextBtn1(): ?string
    {
        return $this->textBtn1;
    }

    public function setTextBtn1(?string $textBtn1): self
    {
        $this->textBtn1 = $textBtn1;

        return $this;
    }

    public function getTextBtn2(): ?string
    {
        return $this->textBtn2;
    }

    public function setTextBtn2(?string $textBtn2): self
    {
        $this->textBtn2 = $textBtn2;

        return $this;
    }

    public function getUrl1(): ?string
    {
        return $this->url1;
    }

    public function setUrl1(?string $url1): self
    {
        $this->url1 = $url1;

        return $this;
    }

    public function getUrl2(): ?string
    {
        return $this->url2;
    }

    public function setUrl2(?string $url2): self
    {
        $this->url2 = $url2;

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
}
