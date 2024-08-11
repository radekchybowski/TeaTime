<?php

namespace App\EventListener;

use App\Entity\Rating;
use App\Service\RatingServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;

/**
 * Rating update listener class.
 */
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Rating::class)]
class RatingUpdateListener
{
    private RatingServiceInterface $service;

    /**
     * Constructor.
     *
     * @param RatingServiceInterface $service rating service
     */
    public function __construct(RatingServiceInterface $service)
    {
        $this->service = $service;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    /**
     * This function is updating average rating of the tea on every rating change.
     *
     * @param Rating $rating rating entity
     */
    public function postUpdate(Rating $rating): void
    {
        $this->service->calculateAverageRating($rating->getTea());
    }
}
