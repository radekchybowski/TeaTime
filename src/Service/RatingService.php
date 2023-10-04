<?php
/**
 * Rating service.
 */

namespace App\Service;

use App\Entity\Rating;
use App\Entity\Tea;
use App\Entity\User;
use App\Repository\RatingRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class RatingService.
 */
class RatingService implements RatingServiceInterface
{

    /**
     * Rating repository.
     */
    private RatingRepository $ratingRepository;

    /**
     * Constructor.
     *
     * @param RatingRepository $ratingRepository Rating repository
     */
    public function __construct(RatingRepository $ratingRepository)
    {
        $this->ratingRepository = $ratingRepository;
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
     * Save entity.
     *
     * @param Rating $rating Rating entity
     */
    public function save(Rating $rating): void
    {
        $this->ratingRepository->save($rating);
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
     * Adding a new rating to Rating table and updating currentRating property on Tea entity.
     *
     * @param Tea  $tea   Tea
     * @param User $user  User
     * @param      $score Score rating
     */
    public function addNewRating(Tea $tea, User $user, $score): void
    {
        $newRating = new Rating();
        $newRating->setRating($score);
        $newRating->setTea($tea);
        $newRating->setAuthor($user);
        $this->save($newRating);

        $sum = 0;
        $i = 0;
        $allRatings = $this->ratingRepository->findByTea($tea);

        foreach ($allRatings as $rating) {
            $sum += $rating->getRating();
            $i++;
        }
        $average = round($sum / $i, 2);
        $tea->setCurrentRating($average);
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
}
