<?php
declare(strict_types=1);

namespace App\Dto;

readonly class BookFilterDto
{
    public function __construct(
        public ?string $title = null,
        public ?string $filter = null
    )
    {
    }
}
