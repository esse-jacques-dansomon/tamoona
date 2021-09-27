<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"title"}, message="Cette categorie exite déja")
 * @UniqueEntity(fields={"slug"}, message="Ce slug exite déja")
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
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
     */
    private ?string $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isDeleted;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private ?string $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="categoryChildren")
     */
    private ?Category $categoryParente;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="categoryParente")
     */
    private Collection $categoryChildren;

    /**
     * @ORM\ManyToMany(targetEntity=Article::class, mappedBy="postsCategory")
     */
    private $postsCategory;



    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->categoryChildren = new ArrayCollection();
        $this->isDeleted = false;
        $this->postsCategory = new ArrayCollection();

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

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

    public function getCategoryParente(): ?self
    {
        return $this->categoryParente;
    }

    public function setCategoryParente(?self $categoryParente): self
    {
        $this->categoryParente = $categoryParente;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getCategoriesChildren(): Collection
    {
        return $this->categoryChildren;
    }

    public function addCategoryChildren(self $category): self
    {
        if (!$this->categoryChildren->contains($category)) {
            $this->categoryChildren[] = $category;
            $category->setCategoryParente($this);
        }

        return $this;
    }

    public function removeCategoryChildren(self $category): self
    {
        if ($this->categoryChildren->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCategoryParente() === $this) {
                $category->setCategoryParente(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }

    /**
     * @return Collection|Article[]
     */
    public function getPostsCategory(): Collection
    {
        return $this->postsCategory;
    }

    public function addPostsCategory(Article $postsCategory): self
    {
        if (!$this->postsCategory->contains($postsCategory)) {
            $this->postsCategory[] = $postsCategory;
            $postsCategory->addPostsCategory($this);
        }

        return $this;
    }

    public function removePostsCategory(Article $postsCategory): self
    {
        if ($this->postsCategory->removeElement($postsCategory)) {
            $postsCategory->removePostsCategory($this);
        }

        return $this;
    }


}
