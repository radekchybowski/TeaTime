<?php
/**
 * Rating fixtures.
 */

namespace DataFixtures;

use App\DataFixtures\AbstractBaseFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Rating;
use App\Entity\User;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class RatingFixtures.
 */
class RatingFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
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

        $this->createMany(500, 'ratings', function () {

            $rating = new Rating();

            /* Setting time of creation and last update */
            $rating->setCreatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $rating->setUpdatedAt(
                \DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );

            $rating->setRating($this->faker->numberBetween(0, 10));

            /**
             * Assigning random user as author.
             *
             * @var User $author author
             */
            $author = $this->getRandomReference('users');
            $rating->setAuthor($author);

            /**
             * Assigning random tea as tea.
             *
             * @var Tea $tea tea
             */
            $tea = $this->getRandomReference('teas');
            $rating->setTea($tea);

            return $rating;
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
        return [UserFixtures::class, TeaFixtures::class];
    }
}
