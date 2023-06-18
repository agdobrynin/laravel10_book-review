<?php
declare(strict_types=1);

namespace App\Services;

use App\Contracts\CacheBookWithReviewsInterface;
use App\Models\Book;
use Psr\SimpleCache\CacheInterface;

readonly class CacheBookWithReviews implements CacheBookWithReviewsInterface
{
    public function __construct(private int $ttl, private CacheInterface $cache)
    {
    }

    public function get(string $bookId): Book
    {
        $key = $this->cacheKey($bookId);

        if ($this->cache->has($key)) {
            return $this->cache->get($key);
        }

        $book = Book::withReviewsWithAvgRatingWithReviewCount($bookId);
        $this->cache->set($key, $book, $this->ttl);

        return $book;
    }

    public function forget(string $bookId): void
    {
        $this->cache->delete($this->cacheKey($bookId));
    }

    private function cacheKey(string $bookId): string
    {
        return self::class . ':' . $bookId;
    }
}
