<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Book;
use Illuminate\Cache\RateLimiter;
use Illuminate\Http\Request;

readonly class ReviewStoreRateLimit
{
    public function __construct(private Request $request, private RateLimiter $rateLimiter)
    {
    }

    /**
     * @throws \LogicException
     */
    public function checkLimit(Book $book, int $maxAttempt, int $decaySeconds = 3600): void
    {
        $rateKey = 'review-store:' . ($this->request->user()?->id ?: $this->request->ip()) . ':' . $book->id;

        if ($this->rateLimiter->tooManyAttempts($rateKey, $maxAttempt)) {
            $seconds = $this->rateLimiter->availableIn($rateKey);

            throw new \LogicException(
                'One review for one book per ' . $decaySeconds . ' seconds. Try again in ' . $seconds . ' seconds.'
            );
        }

        $this->rateLimiter->hit($rateKey, $decaySeconds);
    }
}
