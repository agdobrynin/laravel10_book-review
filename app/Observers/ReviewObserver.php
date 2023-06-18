<?php

namespace App\Observers;

use App\Contracts\CacheBookFilterInterface;
use App\Contracts\CacheBookWithReviewsInterface;
use App\Models\Review;

readonly class ReviewObserver
{
    public function __construct(
        private CacheBookFilterInterface $cacheBookFilter,
        private CacheBookWithReviewsInterface $cacheBookWithReviews,
    )
    {
    }

    /**
     * Handle the Review "created" event.
     */
    public function created(Review $review): void
    {
        $this->cacheBookFilter->forget();
        $this->cacheBookWithReviews->forget($review->book_id);
    }

    /**
     * Handle the Review "updated" event.
     */
    public function updated(Review $review): void
    {
        $this->cacheBookFilter->forget();
        $this->cacheBookWithReviews->forget($review->book_id);
    }

    /**
     * Handle the Review "deleted" event.
     */
    public function deleted(Review $review): void
    {
        $this->cacheBookFilter->forget();
        $this->cacheBookWithReviews->forget($review->book_id);
    }

    /**
     * Handle the Review "restored" event.
     */
    public function restored(Review $review): void
    {
        $this->cacheBookFilter->forget();
        $this->cacheBookWithReviews->forget($review->book_id);
    }

    /**
     * Handle the Review "force deleted" event.
     */
    public function forceDeleted(Review $review): void
    {
        $this->cacheBookFilter->forget();
        $this->cacheBookWithReviews->forget($review->book_id);
    }
}
