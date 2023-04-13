<?php

namespace App\Entity;

use App\Repository\AlbumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timestampable;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Album
{
    use Timestampable;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Ce champ ne peut être vide")]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'Album', targetEntity: Photos::class, orphanRemoval: true, cascade:["persist"])]
    private Collection $photos;

    #[ORM\Column]
    private ?bool $private = null;

    #[ORM\ManyToMany(targetEntity: Category::class, mappedBy: 'album', cascade:["persist"])]
    private Collection $categories;

    #[ORM\Column(options: ["default" => 0], nullable: true)]
    private ?float $save = null;

    #[ORM\Column(options: ["default" => 0], nullable: true)]
    private ?int $view = null;



    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->categories = new ArrayCollection();
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

    /**
     * @return Collection<int, Photos>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photos $photo): self
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setAlbum($this);
        }

        return $this;
    }

    public function removePhoto(Photos $photo): self
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getAlbum() === $this) {
                $photo->setAlbum(null);
            }
        }

        return $this;
    }

    public function isPrivate(): ?bool
    {
        return $this->private;
    }

    public function setPrivate(bool $private): self
    {
        $this->private = $private;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addAlbum($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            $category->removeAlbum($this);
        }

        return $this;
    }

    public function getSave(): ?float
    {
        return $this->save;
    }

    public function setSave(float $save): self
    {
        $this->save = $save;

        return $this;
    }

    public function getView(): ?float
    {
        return $this->view;
    }

    public function setView(float $view): self
    {
        $this->view = $view;

        return $this;
    }

}
