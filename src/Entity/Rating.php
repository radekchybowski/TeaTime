<?php
/**
 * Rating entity.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Rating.
 */
#[ORM\Entity(repositoryClass: RatingRepository::class)]
#[ORM\Table(name: 'ratings')]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: ['get', 'put', 'delete'],
    attributes: [
        'pagination_items_per_page' => 10,
        'order' => [
            'createdAt' => 'DESC',
        ],
    ],
),
    ApiFilter(
        SearchFilter::class,
        properties: [
            'rating' => SearchFilter::STRATEGY_EXACT,
            'author.id' => SearchFilter::STRATEGY_EXACT,
            'tea.id' => SearchFilter::STRATEGY_EXACT,
        ]
    ),
//    ApiFilter(
//        OrderFilter::class,
//        properties: [
//            'createdAt',
//            'currentRating',
//        ]
//    )
]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Created at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeImmutable $createdAt;

    /**
     * Updated at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'integer')]
    #[Assert\Type('integer')]
    #[Assert\NotBlank]
    #[Assert\Range([
        'min' => 0,
        'max' => 10,
    ])]
    private ?int $rating = null;

    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[Assert\Type(User::class)]
    #[Assert\NotBlank]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(targetEntity: Tea::class, cascade: ['remove'], fetch: 'EXTRA_LAZY')]
    #[Assert\Type(Tea::class)]
    #[Assert\NotBlank]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tea $tea = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTea(): ?Tea
    {
        return $this->tea;
    }

    public function setTea(?Tea $tea): self
    {
        $this->tea = $tea;

        return $this;
    }
}
