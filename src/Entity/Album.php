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
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: AlbumRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
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

    #[ORM\OneToMany(mappedBy: 'album', targetEntity: CommentAlbum::class)]
    private Collection $commentAlbums;

    #[ORM\Column(type: 'string')]
    private ?string $coverName = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'photos', fileNameProperty: 'coverName')]
    private ?File $coverFile = null;

    #[ORM\Column]
    private ?bool $banniere = null;



    public function __construct()
    {
        $this->photos = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->commentAlbums = new ArrayCollection();
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

    /**
     * @return Collection<int, CommentAlbum>
     */
    public function getCommentAlbums(): Collection
    {
        return $this->commentAlbums;
    }

    public function addCommentAlbum(CommentAlbum $commentAlbum): self
    {
        if (!$this->commentAlbums->contains($commentAlbum)) {
            $this->commentAlbums->add($commentAlbum);
            $commentAlbum->setAlbum($this);
        }

        return $this;
    }

    public function removeCommentAlbum(CommentAlbum $commentAlbum): self
    {
        if ($this->commentAlbums->removeElement($commentAlbum)) {
            // set the owning side to null (unless already changed)
            if ($commentAlbum->getAlbum() === $this) {
                $commentAlbum->setAlbum(null);
            }
        }

        return $this;
    }

    public function setCoverName(?string $coverName): void
    {
        $this->coverName = $coverName;
    }

    public function getcoverName(): ?string
    {
        return $this->coverName;
    }

        /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $coverFile
     */
    public function setCoverFile(?File $coverFile = null): void
    {
        $this->coverFile = $coverFile;

        if (null !== $coverFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getcoverFile(): ?File
    {
        return $this->coverFile;
    }

    public function isBanniere(): ?bool
    {
        return $this->banniere;
    }

    public function setBanniere(bool $banniere): self
    {
        $this->banniere = $banniere;

        return $this;
    }

}
