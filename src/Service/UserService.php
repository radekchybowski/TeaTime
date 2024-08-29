<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserService.
 */
class UserService implements UserServiceInterface
{
    /**
     * User repository.
     */
    private UserRepository $userRepository;

    /**
     * Tea Service.
     */
    private TeaServiceInterface $teaService;

    /**
     * Avatar Service.
     */
    private AvatarServiceInterface $avatarService;

    /**
     * Rating Service.
     */
    private RatingServiceInterface $ratingService;

    /**
     * Comment Service.
     */
    private CommentServiceInterface $commentService;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Constructor.
     *
     * @param UserRepository              $userRepository User repository
     * @param TeaServiceInterface         $teaService     Tea Service
     * @param AvatarServiceInterface      $avatarService  Avatar Service
     * @param PaginatorInterface          $paginator      Paginator
     * @param UserPasswordHasherInterface $passwordHasher Password hasher
     */
    public function __construct(UserRepository $userRepository, TeaServiceInterface $teaService, AvatarServiceInterface $avatarService, RatingServiceInterface $ratingService, CommentServiceInterface $commentService, PaginatorInterface $paginator, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->teaService = $teaService;
        $this->avatarService = $avatarService;
        $this->ratingService = $ratingService;
        $this->commentService = $commentService;
        $this->paginator = $paginator;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->userRepository->queryAll(),
            $page,
            UserRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Change password.
     *
     * @param User          $user User entity
     * @param FormInterface $form Form
     */
    public function changePassword(User $user, FormInterface $form): void
    {
        $user->setPassword(
            $this->passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
        $this->userRepository->save($user);
    }

    /**
     * Save entity.
     *
     * @param User $user User entity
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }

    /**
     * Delete entity.
     *
     * @param User $user User entity
     */
    public function delete(User $user): void
    {
        $this->deleteUserOrphans($user);
        $this->userRepository->remove($user);
    }

    /**
     * Delete entities that belongs to User entity.
     *
     * @param User $user User entity
     */
    public function deleteUserOrphans(User $user): void
    {
        $this->ratingService->deleteRatingByAuthor($user);
        $this->teaService->deleteTeaByAuthor($user);
        $this->commentService->deleteCommentsByAuthor($user);
        $this->avatarService->deleteByUser($user);
    }

    /**
     * Checks if it's safe to delete an admin.
     *
     * @param User $userToDelete user that will be deleted if true
     *
     * @return bool true if there still will be admin after deletion
     */
    public function isLastAdmin(User $userToDelete): bool
    {
        $users = $this->userRepository->findAll();
        $admins = 0;
        $isAdmin = false;
        foreach ($users as $user) {
            if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                ++$admins;
                if ($user->getId() === $userToDelete->getId()) {
                    $isAdmin = true;
                }
            }
        }
        if ((1 === $admins) && $isAdmin) {
            return true;
        }

        return false;
    }
}
