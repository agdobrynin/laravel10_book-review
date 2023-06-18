<?php
declare(strict_types=1);

namespace App\Services;

use App\Contracts\CacheBookFilterInterface;
use App\Dto\BookFilterDto;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

readonly class CacheBookFilter implements CacheBookFilterInterface
{
    public function __construct(private int $ttl, private Repository $cache)
    {
    }

    public function get(BookFilterDto $bookFilterDto, Builder $books): Collection
    {
        $cache = $this->cache->tags(self::class);
        $key = md5(serialize($bookFilterDto));

        if ($cache->has($key)) {
            return $cache->get($key);
        }

        $collection = $books->get();
        $cache->put($key, $collection, $this->ttl);

        return $collection;
    }

    public function forget(): void
    {
        $this->cache->tags(self::class)->flush();
    }
}
