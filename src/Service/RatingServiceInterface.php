<?php
/**
 * Rating service interface.
 */

namespace App\Service;

use App\Entity\Rating;
use App\Entity\Tea;
use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Interface RatingServiceInterface.
 */
interface RatingServiceInterface
{
    /**
     * Save entity.
     *
     * @param Rating $rating Rating entity
     */
    public function save(Rating $rating): void;

    /**
     * Delete entity.
     *
     * @param Rating $rating Rating entity
     */
    public function delete(Rating $rating): void;

    /**
     * Find by id.
     *
     * @param int $id Rating id
     *
     * @return Rating|null Rating entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Rating;

    /**
     * Returns Users rating if Tea was previously rated.
     *
     * @param User $user User
     * @param Tea  $tea  Tea
     *
     * @return Rating|null Rating
     */
    public function findPreviousRating(User $user, Tea $tea) : ?Rating;

    /**
     * Delete all entities where param user = author.
     *
     * @param User $user User entity
     */
    public function deleteRatingByAuthor(User $user): void;

    /**
     * Calculate latest average rating and updates currentRating property on $tea entity.
     *
     * @param Tea $tea tea
     *
     * @return void
     */
    public function calculateAverageRating(Tea $tea) : void;
}
