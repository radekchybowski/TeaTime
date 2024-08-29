<?php
/**
 * Rating service.
 */

namespace App\Service;

use App\Entity\Rating;
use App\Entity\Tea;
use App\Entity\User;
use App\Repository\RatingRepository;
use App\Repository\TeaRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class RatingService.
 */
class RatingService implements RatingServiceInterface
{
    /**
     * Constructor.
     *
     * @param RatingRepository $ratingRepository Rating repository
     * @param TeaRepository    $teaRepository
     */
    public function __construct(
        private RatingRepository $ratingRepository,
        private TeaRepository $teaRepository
    ) {
    }

    /**
     * Find by id.
     *
     * @param int $id Rating id
     *
     * @return Rating|null Rating entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Rating
    {
        return $this->ratingRepository->findOneById($id);
    }

    /**
     * Save Rating entity and calling calculateAverateRating method.
     *
     * @param Rating $rating Rating entity
     */
    public function save(Rating $rating): void
    {
        $oldRating = $this->ratingRepository->findOneBy(['tea' => $rating->getTea(), 'author' => $rating->getAuthor()]);
        if (null !== $oldRating) {
            $score = $rating->getRating();
            $oldRating->setRating($score);
            $this->ratingRepository->save($oldRating);
        } else {
            $this->ratingRepository->save($rating);
        }
        $this->calculateAverageRating($rating->getTea());
    }

    /**
     * Delete entity.
     *
     * @param Rating $rating Rating entity
     */
    public function delete(Rating $rating): void
    {
        $this->ratingRepository->delete($rating);
    }

    /**
     * Delete all entities where param user = author.
     *
     * @param User $user User entity
     */
    public function deleteRatingByAuthor(User $user): void
    {
        /** @var Rating[] $ratingsArray Array of ratings to delete */
        $ratingsArray = $this->ratingRepository->findByAuthor($user);

        foreach ($ratingsArray as $rating) {
            $this->ratingRepository->delete($rating);
        }
    }

    /**
     * Returns Users rating if Tea was previously rated.
     *
     * @param User $user User
     * @param Tea  $tea  Tea
     *
     * @return Rating|null Rating
     */
    public function findPreviousRating(User $user, Tea $tea): ?Rating
    {
        return $this->ratingRepository->findOneBy(['tea' => $tea, 'author' => $user]);
    }

    /**
     * Calculate latest average rating and updates currentRating property on $tea entity.
     *
     * @param Tea|null $tea tea
     */
    public function calculateAverageRating(?Tea $tea): void
    {
        if (null === $tea) {
            return;
        }

        $sum = 0;
        $i = 0;
        $allRatings = $this->ratingRepository->findByTea($tea);

        if (!count($allRatings)) {
            $tea->setCurrentRating(0);
            $this->teaRepository->save($tea);

            return;
        }
        foreach ($allRatings as $rating) {
            $score = $rating->getRating();
            if (0 === $score) {
                continue;
            }
            $sum += $score;
            ++$i;
        }
        $average = round($sum / $i, 1);
        $tea->setCurrentRating($average);
        $this->teaRepository->save($tea);
    }
}
