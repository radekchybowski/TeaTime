<?php
/**
 * User service interface.
 */

namespace App\Service;

use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Interface UserServiceInterface.
 */
interface UserServiceInterface
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
     * Change password.
     *
     * @param User          $user User entity
     * @param FormInterface $form Form
     */
    public function changePassword(User $user, FormInterface $form): void;

    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void;

    /**
     * Delete entity.
     *
     * @param User $user User entity
     */
    public function delete(User $user): void;

    /**
     * Delete entities that belongs to User entity.
     *
     * @param User $user User entity
     */
    public function deleteUserOrphans(User $user): void;

    /**
     * Checks if it's safe to delete an admin.
     *
     * @param User $userToDelete user that will be deleted if true
     *
     * @return bool true if there still will be admin after deletion
     */
    public function isLastAdmin(User $userToDelete): bool;
}
