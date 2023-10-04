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
     * Delete all ratings where User is author.
     *
     * @param User $user User
     */
    public function addNewRating(Tea $tea, User $user, $score): void;
}
