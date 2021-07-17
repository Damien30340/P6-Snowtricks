<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 * @ORM\Table(name="trick", indexes={@ORM\Index(columns={"name", "content"}, flags={"fulltext"})})
 * @UniqueEntity("name")
 */
class Trick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"loadMore"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"loadMore"})
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 30,
     *      minMessage = "Le titre doit contenir au minimum {{ limit }} caractères",
     *      maxMessage = "Le titre ne doit pas dépasser les {{ limit }} caractères"
     * )
     * @Assert\Unique()
     */
    private ?string $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"loadMore"})
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 10,
     *      max = 500,
     *      minMessage = "La déscription doit contenir au minimum {{ limit }} caractères",
     *      maxMessage = "Le titre ne doit pas dépasser les {{ limit }} caractères"
     * )
     */
    private ?string $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"loadMore"})
     * @Assert\NotBlank
     */
    private  $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private  $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="tricks")
     */
    private ?Category $category;

    /**
     * @ORM\OneToMany(targetEntity=Picture::class, mappedBy="trick", orphanRemoval=true, cascade={"persist", "remove"}, fetch="EAGER")
     * @Assert\Valid()
     * @var Collection<int, Picture>
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="trick", orphanRemoval=true, cascade={"persist", "remove"})
     * @Assert\Valid()
     * @var Collection<int, Video>
     */
    private $videos;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, orphanRemoval=true, mappedBy="trick", cascade={"remove"})
     */
    private $comments;

    /**
     * @var string
     * @Groups({"loadMore"})
     */
    private string $slug;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->comments = new ArrayCollection();
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

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

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setTrick($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->removeElement($picture)) {
            // set the owning side to null (unless already changed)
            if ($picture->getTrick() === $this) {
                $picture->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Video[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): self
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }



    public function getDefaultPicture(){
        $first =  $this->pictures->first();
        return $first ? $first->getFilename() : "assets/img/tricks/snowboard1.jpeg";
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }

    public function setSlug($slugger)
    {
        $this->slug = $slugger;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
