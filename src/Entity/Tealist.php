<?php
/**
 * Tealist entity.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TealistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tealist.
 */
#[ORM\Entity(repositoryClass: TealistRepository::class)]
#[ORM\Table(name: 'tealists')]
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
            'title' => SearchFilter::STRATEGY_PARTIAL,
            'author.id' => SearchFilter::STRATEGY_EXACT,
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
class Tealist
{
    /**
     * Primary key.
     */
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
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Updated at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * Title.
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $title = null;

    /**
     * Author.
     */
    #[ORM\ManyToOne(fetch: 'EXTRA_LAZY', inversedBy: 'tealists')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    private User $author;

    /**
     * Teas.
     */
    #[Assert\Valid]
    #[ORM\ManyToMany(targetEntity: Tea::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinTable(name: 'tealists_teas')]
    private ?Collection $teas;

    public function __construct()
    {
        $this->teas = new ArrayCollection();
    }

    /**
     * Entity id.
     *
     * @return int|null $id Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for title.
     *
     * @return string|null $title title
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for title.
     *
     * @param string $title title
     *
     * @return $this
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter for createdAt.
     *
     * @return \DateTimeImmutable|null $createdAt Createdat
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for createdAt.
     *
     * @param \DateTimeImmutable $createdAt Created at
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for updatedAt.
     *
     * @return \DateTimeImmutable|null $updatedAt Updated at
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Setter for updatedAt.
     *
     * @param \DateTimeImmutable $updatedAt Updated at
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Getter for Author.
     *
     * @return User|null $author author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Setter for Author.
     *
     * @param User|null $author author
     *
     * @return $this
     */
    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Getter for teas.
     *
     * @return Collection<int, Tea> $teas Tea
     */
    public function getTeas(): Collection
    {
        return $this->teas;
    }

    /**
     * Adding Tea to Teas collection.
     *
     * @param Tea $tea Tea
     *
     * @return $this
     */
    public function addTea(Tea $tea): self
    {
        if (!$this->teas->contains($tea)) {
            $this->teas->add($tea);
        }

        return $this;
    }

    /**
     * Removing Tea to Teas collection.
     *
     * @param Tea $tea Tea
     *
     * @return $this
     */
    public function removeTea(Tea $tea): self
    {
        $this->teas->removeElement($tea);

        return $this;
    }
}
