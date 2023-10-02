<?php
/**
 * User fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserFixtures.
 */
class UserFixtures extends AbstractBaseFixtures
{
    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Load data.
     */
    protected function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(10, 'users', function (int $i) {
            $user = new User();
            $user->setEmail(sprintf('user%d@example.com', $i));
            $user->setRoles([UserRole::ROLE_USER->value]);
            $user->setName($this->faker->firstName);
            $user->setSurname($this->faker->lastName);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'twojStaryXDXD1223!@3'
                )
            );

            return $user;
        });

        $this->createMany(3, 'admins', function (int $i) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@example.com', $i));
            $user->setRoles([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
            $user->setName($this->faker->firstName);
            $user->setSurname($this->faker->lastName);
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    'twojStaryXDXD1223!@3'
                )
            );

            return $user;
        });

        $this->manager->flush();
    }
}
