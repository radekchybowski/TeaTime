<?php
/**
 * User entity.
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Enum\UserRole;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User.
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ORM\UniqueConstraint(name: 'email_idx', columns: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * Primary key.
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Email.
     */
    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email;

    /**
     * Roles.
     *
     * @var array<int, string> Roles
     */
    #[ORM\Column(type: 'json')]
    private array $roles = [];

    /**
     * Password.
     */
    #[ORM\Column(type: 'string')]
    //    #[Assert\NotBlank]
    private ?string $password;

    /**
     * Plain password.
     */
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $plainPassword = null;

    /**
     * Avatar.
     */
    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Avatar::class, cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    private ?Avatar $avatar = null;

    /**
     * First name.
     */
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $name = null;

    /**
     * Surname.
     */
    #[ORM\Column(length: 64, nullable: true)]
    private ?string $surname = null;

    /**
     * Comments.
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class, cascade: ['remove'])]
    private ?Collection $comments;

    /**
     * Tealists.
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Tealist::class, orphanRemoval: true)]
    private ?Collection $tealists;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: ApiToken::class, orphanRemoval: true)]
    private Collection $apiTokens;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->collections = new ArrayCollection();
        $this->apiTokens = new ArrayCollection();
    }

    /**
     * Getter for id.
     *
     * @return int|null Id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for email.
     *
     * @return string|null Email
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for email.
     *
     * @param string $email Email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @return string User identifier
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     *
     * @return string Username
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Getter for roles.
     *
     * @return array<int, string> Roles
     *
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRole::ROLE_USER->value;

        return array_unique($roles);
    }

    /**
     * Setter for roles.
     *
     * @param array<int, string> $roles Roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Getter for password.
     *
     * @return string|null the hashed password for this user
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Setter for password.
     *
     * @param string $password User password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @return string|null Salt
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Removes sensitive information from the token.
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Getter for avatar.
     *
     * @return Avatar|null Avatar profile picture
     */
    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    /**
     * Setter for avatar.
     *
     * @param Avatar $avatar Avatar profile picture
     *
     * @return $this
     */
    public function setAvatar(Avatar $avatar): self
    {
        // set the owning side of the relation if necessary
        if ($avatar->getUser() !== $this) {
            $avatar->setUser($this);
        }

        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Getter for name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for name.
     *
     * @param string|null $name Name
     *
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Getter for surname.
     *
     * @return string|null Surname
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * Setter for surname.
     *
     * @param string|null $surname Surname
     *
     * @return $this
     */
    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Getter for plain password used in password changing process.
     *
     * @return string|null Plain password
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * Setter for plain password.
     *
     * @param string|null $plainPassword Plain password
     *
     * @return $this
     */
    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @return Collection<int, Collection>
     */
    public function getCollections(): Collection
    {
        return $this->collections;
    }

    /**
     * Setter for Tealist
     *
     * @param Tealist $tealist Tealist
     *
     * @return $this
     */
    public function addTealist(Tealist $tealist): self
    {
        if (!$this->tealists->contains($tealist)) {
            $this->tealists->add($tealist);
            $tealist->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param Tealist $tealist Tealist
     *
     * @return $this
     */
    public function removeTealist(Tealist $tealist): self
    {
        if ($this->tealists->removeElement($tealist)) {
            // set the owning side to null (unless already changed)
            if ($tealist->getAuthor() === $this) {
                $tealist->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApiToken>
     */
    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function addApiToken(ApiToken $apiToken): static
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens->add($apiToken);
            $apiToken->setUser($this);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): static
    {
        if ($this->apiTokens->removeElement($apiToken)) {
            // set the owning side to null (unless already changed)
            if ($apiToken->getUser() === $this) {
                $apiToken->setUser(null);
            }
        }

        return $this;
    }
}
