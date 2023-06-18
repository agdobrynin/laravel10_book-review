<?php

namespace App\Contracts;

use App\Dto\BookFilterDto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface CacheBookFilterInterface
{
    public function get(BookFilterDto $bookFilterDto, Builder $books): Collection;

    public function forget(): void;
}
