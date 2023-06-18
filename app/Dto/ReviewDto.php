<?php
declare(strict_types=1);

namespace App\Dto;

readonly class ReviewDto
{
    public function __construct(public string $content, public int $rating)
    {
    }
}
