<?php
/**
 * Comment entity.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Comment.
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'comments')]
#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: [
        'get',
        'put' => [
            'security' => "is_granted('EDIT', object)",
        ],
        'delete' => [
            'security' => "is_granted('DELETE', object)",
        ],
    ],
    attributes: [
        'pagination_items_per_page' => 10,
        'order' => [
            'title' => 'ASC',
        ],
    ],
),
    ApiFilter(
        SearchFilter::class,
        properties: [
            'title' => SearchFilter::STRATEGY_PARTIAL,
            'tea' => SearchFilter::STRATEGY_EXACT,
            'author' => SearchFilter::STRATEGY_EXACT,
        ]
    ),
    ApiFilter(
        OrderFilter::class,
        properties: [
            'createdAt',
        ]
    )
]
class Comment
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
     * Title.
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Type('string')]
    #[Assert\Length(min: 3, max: 255)]
    #[Groups('read_Tea')]
    private ?string $title = null;

    /**
     * Content of the comment.
     */
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Groups('read_Tea')]
    private ?string $content = null;

    /**
     * Created at.
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(\DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    #[Groups('read_Tea')]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * Tea.
     */
    #[ORM\ManyToOne(targetEntity: Tea::class, fetch: 'LAZY', inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(Tea::class)]
    private ?Tea $tea = null;

    /**
     * Author.
     */
    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY', inversedBy: 'comments')]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    #[Groups('read_Tea')]
    private ?User $author = null;

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
     * @param string|null $title Title
     *
     * @return $this
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Getter for content.
     *
     * @return string|null Content
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     *
     * @param string $content Content
     *
     * @return $this
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
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
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Getter for tea.
     *
     * @return Tea|null Tea
     */
    public function getTea(): ?Tea
    {
        return $this->tea;
    }

    /**
     * Setter for tea.
     *
     * @param Tea|null $tea Tea
     *
     * @return $this
     */
    public function setTea(?Tea $tea): self
    {
        $this->tea = $tea;

        return $this;
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
}
