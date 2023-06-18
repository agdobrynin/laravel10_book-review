<?php

namespace App\Providers;

use App\Contracts\CacheBookFilterInterface;
use App\Contracts\CacheBookWithReviewsInterface;
use App\Services\CacheBookFilter;
use App\Services\CacheBookWithReviews;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            CacheBookFilterInterface::class,
            fn() => new CacheBookFilter(
                env('BOOK_FILTER_CACHE_TTL', 3600),
                Cache::store('redis')
            )
        );

        $this->app->bind(
            CacheBookWithReviewsInterface::class,
            fn() => new CacheBookWithReviews(
                env('BOOK_WITH_REVIEWS_CACHE_TTL', 3600),
                Cache::store('redis')
            )
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
