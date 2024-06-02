<?php
/**
 * Tealist service interface.
 */

namespace App\Service;

use App\Entity\Tealist;
use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TealistServiceInterface.
 */
interface TealistServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int  $page   Page number
     * @param User $author Author
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(int $page, User $author): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Tealist $tealist Tealist entity
     */
    public function save(Tealist $tealist): void;

    /**
     * Delete entity.
     *
     * @param Tealist $tealist Tealist entity
     */
    public function delete(Tealist $tealist): void;

    /**
     * Find by id.
     *
     * @param int $id Tealist id
     *
     * @return Tealist|null Tealist entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Tealist;

    /**
     * Find by Author.
     *
     * @param User $author Author
     *
     * @return Tealist|null Tealist
     */
    public function findAllByAuthor(User $author): ?Array;

    /**
     * Delete all tealists where User is author.
     *
     * @param User $user User
     */
    public function deleteTealistByAuthor(User $user): void;
}
