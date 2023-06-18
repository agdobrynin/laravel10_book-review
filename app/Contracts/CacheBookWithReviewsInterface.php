<?php

namespace App\Contracts;

use App\Models\Book;

interface CacheBookWithReviewsInterface
{
    public function get(string $bookId): Book;

    public function forget(string $bookId): void;
}
