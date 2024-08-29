<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\UserServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

/**
 * User Delete listener class.
 */
#[AsEntityListener(event: Events::preRemove, method: 'deleteUser', entity: User::class)]
class UserDeleteListener
{
    private UserServiceInterface $service;

    /**
     * Constructor.
     *
     * @param UserServiceInterface $service rating service
     */
    public function __construct(UserServiceInterface $service)
    {
        $this->service = $service;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    /**
     * This function is updating average rating of the tea on every rating change.
     *
     * @param User $user
     */
    public function deleteUser(User $user): void
    {
        $this->service->deleteUserOrphans($user);
    }
}
