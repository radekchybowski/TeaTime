<?php
/**
 * Tea entity.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TeaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tea.
 */
#[ORM\Entity(repositoryClass: TeaRepository::class)]
#[ORM\Table(name: 'teas')]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get', 'patch', 'delete'],
    attributes: [
        'pagination_items_per_page' => 10,
        'order' => [
            'createdAt' => 'DESC',
        ],
    ],
    denormalizationContext: ['groups' => ['write_Tea']],
    normalizationContext: ['groups' => ['read_Tea']]
),
    ApiFilter(
        SearchFilter::class,
        properties: [
            'title' => SearchFilter::STRATEGY_PARTIAL,
            'category.title' => SearchFilter::STRATEGY_PARTIAL,
            'category.id' => SearchFilter::STRATEGY_EXACT,
            'tags' => SearchFilter::STRATEGY_PARTIAL,
            'author.id' => SearchFilter::STRATEGY_EXACT,
            'ingredients' => SearchFilter::STRATEGY_PARTIAL,
            'vendor' => SearchFilter::STRATEGY_PARTIAL,
        ]
    ),
    ApiFilter(
        OrderFilter::class,
        properties: [
            'createdAt',
            'currentRating',
        ]
    )
]
class Tea
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups('read_Tea')]
    private ?int $id = null;

    /**
     * Created at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups('read_Tea')]
    private ?\DateTimeImmutable $createdAt;

    /**
     * Updated at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'update')]
    #[Groups('read_Tea')]
    private ?\DateTimeImmutable $updatedAt;

    /**
     * Title.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    #[Groups(['read_Tea', 'write_Tea'])]
    private ?string $title;

    /**
     * Category.
     */
    #[ORM\ManyToOne(targetEntity: Category::class, fetch: 'EXTRA_LAZY')]
    #[Assert\Type(Category::class)]
    #[Assert\NotBlank]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['read_Tea', 'write_Tea'])]
    private ?Category $category;

    /**
     * Tags.
     */
    #[Assert\Valid]
    #[ORM\ManyToMany(targetEntity: Tag::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[ORM\JoinTable(name: 'teas_tags')]
    #[Groups(['read_Tea', 'write_Tea'])]
    private ?Collection $tags;

    /**
     * Author.
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    #[Groups(['read_Tea', 'write_Tea'])]
    private ?User $author;

    /**
     * Description.
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Type('string')]
    #[Groups(['read_Tea', 'write_Tea'])]
    private ?string $description = null;

    /**
     * Ingredients.
     */
    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read_Tea', 'write_Tea'])]
    private ?string $ingredients = null;

    /**
     * Steep Time in seconds.
     */
    #[ORM\Column(nullable: true)]
    #[Groups(['read_Tea', 'write_Tea'])]
    private ?int $steepTime = null;

    /**
     * Steep temperature in Celsius.
     */
    #[ORM\Column(nullable: true)]
    #[Groups(['read_Tea', 'write_Tea'])]
    private ?int $steepTemp = null;

    /**
     * Region of origin.
     */
    #[ORM\Column(length: 64, nullable: true)]
    #[Groups(['read_Tea', 'write_Tea'])]
    private ?string $region = null;

    /**
     * Vendor/Store.
     */
    #[ORM\Column(length: 64, nullable: true)]
    #[Groups(['read_Tea', 'write_Tea'])]
    private ?string $vendor = null;

    /**
     * Comments.
     */
    #[ORM\OneToMany(mappedBy: 'tea', targetEntity: Comment::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    #[Groups(['read_Tea', 'write_Tea'])]
    private ?Collection $comments = null;

    /**
     * Ratings.
     */
    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'tea', fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private ?Collection $ratings;

    /**
     * Current rating average from all ratings.
     */
    #[ORM\Column(nullable: true)]
    #[Groups('read_Tea')]
    private ?float $currentRating = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->ratings = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for created at.
     *
     * @return \DateTimeImmutable|null Created at
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for created at.
     *
     * @param \DateTimeImmutable $createdAt Created at
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for updated at.
     *
     * @return \DateTimeImmutable|null Updated at
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Setter for updated at.
     *
     * @param \DateTimeImmutable $updatedAt Updated at
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for title.
     *
     * @return string|null Title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title Title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for category.
     *
     * @return Category|null Category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category.
     *
     * @param Category|null $category Category
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * Getter for tags.
     *
     * @return Collection<int, Tag> Tags collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag.
     *
     * @param Tag $tag Tag entity
     */
    public function addTag(Tag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
    }

    /**
     * Remove tag.
     *
     * @param Tag $tag Tag entity
     */
    public function removeTag(Tag $tag): void
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Getter for author.
     *
     * @return User|null Author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for author.
     *
     * @param User|null $author Author
     *
     * @return $this
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Getter for description.
     *
     * @return string|null Description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Setter for description.
     *
     * @param string|null $description Description
     *
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Getter for ingredients.
     *
     * @return string|null Ingredients
     */
    public function getIngredients(): ?string
    {
        return $this->ingredients;
    }

    /**
     * Getter for ingredients.
     *
     * @param string|null $ingredients Ingredients
     *
     * @return $this
     */
    public function setIngredients(?string $ingredients): self
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    /**
     * Getter for steep time.
     *
     * @return int|null Steep time
     */
    public function getSteepTime(): ?int
    {
        return $this->steepTime;
    }

    /**
     * Setter for steep time.
     *
     * @param int|null $steepTime Steep time
     *
     * @return $this
     */
    public function setSteepTime(?int $steepTime): self
    {
        $this->steepTime = $steepTime;

        return $this;
    }

    /**
     * Getter for steep temperature.
     *
     * @return int|null Steep temperature
     */
    public function getSteepTemp(): ?int
    {
        return $this->steepTemp;
    }

    /**
     * Setter for steep temperature.
     *
     * @param int|null $steepTemp Steep temperature
     *
     * @return $this
     */
    public function setSteepTemp(?int $steepTemp): self
    {
        $this->steepTemp = $steepTemp;

        return $this;
    }

    /**
     * Getter for region.
     *
     * @return string|null Region
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * Setter for region.
     *
     * @param string|null $region Region
     *
     * @return $this
     */
    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Getter for vendor.
     *
     * @return string|null Vendor
     */
    public function getVendor(): ?string
    {
        return $this->vendor;
    }

    /**
     * Setter for vendor.
     *
     * @param string|null $vendor Vendor
     *
     * @return $this
     */
    public function setVendor(?string $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * Getter for Comments collection.
     *
     * @return Collection<int, Comment> Comments
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Adding another comment.
     *
     * @param Comment $comment Comment
     *
     * @return $this
     */
    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTea($this);
        }

        return $this;
    }

    /**
     * Removing one of the comments.
     *
     * @param Comment $comment Comment
     *
     * @return $this
     */
    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTea() === $this) {
                $comment->setTea(null);
            }
        }

        return $this;
    }

    /**
     * Getter for Ratings collection.
     *
     * @return Collection<int, Rating> Ratings
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    /**
     * Adding another rating.
     *
     * @param Rating $rating Rating
     *
     * @return $this
     */
    public function addRating(Rating $rating): self
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setTea($this);
        }

        return $this;
    }

    /**
     * Removing one of the ratings.
     *
     * @param Rating $rating Rating
     *
     * @return $this
     */
    public function removeRating(Rating $rating): self
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getTea() === $this) {
                $rating->setTea(null);
            }
        }

        return $this;
    }

    /**
     * Getter for current rating.
     *
     * @return float|null Current rating
     */
    public function getCurrentRating(): ?float
    {
        return $this->currentRating;
    }

    /**
     * Setter for current rating.
     *
     * @param float|null $currentRating Current rating
     *
     * @return $this
     */
    public function setCurrentRating(?float $currentRating): self
    {
        $this->currentRating = $currentRating;

        return $this;
    }

    #[ORM\PreRemove]
    public function removeRatings(): void
    {
        foreach ($this->ratings as $rating) {
            $this->removeRating($rating);
        }
    }
}
