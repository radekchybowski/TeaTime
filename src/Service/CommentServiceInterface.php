<?php
/**
 * Comment service interface.
 */

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Tea;
use App\Entity\User;
use Doctrine\ORM\NonUniqueResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CommentServiceInterface.
 */
interface CommentServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * Save entity.
     *
     * @param Comment $comment Comment entity
     */
    public function save(Comment $comment): void;

    /**
     * Delete entity.
     *
     * @param Comment $comment Comment entity
     */
    public function delete(Comment $comment): void;

    /**
     * Find by id.
     *
     * @param int $id Comment id
     *
     * @return Comment|null Comment entity
     *
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Comment;

    /**
     * Deletes all entities where param user = author.
     *
     * @param User $user User entity
     */
    public function deleteCommentsByAuthor(User $user): void;

    /**
     * Deletes all entities where param tea.
     *
     * @param Tea $tea
     */
    public function deleteCommentsByTea(Tea $tea): void;
}
