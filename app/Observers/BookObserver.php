<?php

namespace App\Observers;

use App\Contracts\CacheBookFilterInterface;
use App\Models\Book;

readonly class BookObserver
{
    public function __construct(
        private CacheBookFilterInterface $cacheBookFilter,
    )
    {
    }

    /**
     * Handle the Book "created" event.
     */
    public function created(Book $book): void
    {
        $this->cacheBookFilter->forget();
    }

    /**
     * Handle the Book "updated" event.
     */
    public function updated(Book $book): void
    {
        $this->cacheBookFilter->forget();
    }

    /**
     * Handle the Book "deleted" event.
     */
    public function deleted(Book $book): void
    {
        $this->cacheBookFilter->forget();
    }

    /**
     * Handle the Book "restored" event.
     */
    public function restored(Book $book): void
    {
        $this->cacheBookFilter->forget();
    }

    /**
     * Handle the Book "force deleted" event.
     */
    public function forceDeleted(Book $book): void
    {
        $this->cacheBookFilter->forget();
    }
}
