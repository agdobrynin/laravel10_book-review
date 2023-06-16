<?php
declare(strict_types=1);

namespace Tests;

readonly class DateWithRating
{
    public function __construct(public int $rating, public ?string $date = null)
    {
    }
}
