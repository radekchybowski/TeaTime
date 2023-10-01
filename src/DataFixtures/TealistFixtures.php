<?php
/**
 * Tealist fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Tealist;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class TealistFixtures.
 */
class TealistFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(13, 'tealists', function () {

            $tealist = new Tealist();

            $tealist->setTitle('label.tealist.favorites');

            /* Setting time of creation and last update */
            $tealist->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $tealist->setUpdatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            /**
             * Assigning random user as author.
             *
             * @var User $author
             */
            $author = $this->getRandomReference('users');
            $tealist->setAuthor($author);

            /**
             * Assigning random teas to Tealist object.
             *
             * @var array<array-key, Tea> $teas
             */
            $teas = $this->getRandomReferences(
                'teas',
                $this->faker->numberBetween(2, 10)
            );
            foreach ($teas as $tea) {
                $tealist->addTea($tea);
            }

            return $tealist;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class, 1: TagFixtures::class, 2: UserFixtures::class}
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
