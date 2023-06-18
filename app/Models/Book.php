<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['title', 'author'];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeTitle(Builder $builder, string $title): Builder
    {
        return $builder->where('title', 'like', "%{$title}%");
    }

    public function scopeWithReviewsWithAvgRatingWithReviewCount(Builder $builder, string $bookId): Book
    {
        return Book::with(['reviews' => fn($q) => $q->latest()])
            ->withReviewsAvgRating()
            ->withReviewsCount()->findOrFail($bookId);
    }

    public function scopeWithReviewsCount(
        Builder            $builder,
        ?DateTimeInterface $from = null,
        ?DateTimeInterface $to = null
    ): Builder
    {
        return $builder->withCount([
            'reviews' => fn(Builder $q) => $this->dateFilter($q, $from, $to)
        ]);
    }

    public function scopeWithReviewsAvgRating(
        Builder            $builder,
        ?DateTimeInterface $from = null,
        ?DateTimeInterface $to = null
    ): Builder
    {
        return $builder->withAvg(
            [
                'reviews' => fn(Builder $q) => $this->dateFilter($q, $from, $to)
            ],
            'rating'
        );
    }

    public function scopePopular(
        Builder            $builder,
        ?DateTimeInterface $from = null,
        ?DateTimeInterface $to = null
    ): Builder
    {
        return $builder->withReviewsCount($from, $to)
            ->orderBy('reviews_count', 'desc');
    }

    public function scopeHiRated(
        Builder            $builder,
        ?DateTimeInterface $from = null,
        ?DateTimeInterface $to = null
    ): Builder
    {
        return $builder->withReviewsAvgRating($from, $to)
            ->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopePopularLastMonth(Builder $builder): Builder
    {
        return $builder->popular(now()->addMonths(-1), now())
            ->hiRated(now()->addMonths(-1), now())
            ->having('reviews_count', '>', 0);
    }

    public function scopePopularLast6Months(Builder $builder): Builder
    {
        return $builder->popular(now()->addMonths(-6), now())
            ->hiRated(now()->addMonths(-6), now())
            ->having('reviews_count', '>', 0);
    }

    public function scopeHiRatedLastMonth(Builder $builder): Builder
    {
        return $builder->hiRated(now()->addMonths(-1), now())
            ->popular(now()->addMonths(-1), now())
            ->having('reviews_count', '>', 0);
    }

    public function scopeHiRatedLast6Months(Builder $builder): Builder
    {
        return $builder->hiRated(now()->addMonths(-6), now())
            ->popular(now()->addMonths(-6), now())
            ->having('reviews_count', '>', 0);
    }

    private function dateFilter(Builder $builder, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null)
    {
        return $builder
            ->when($from, fn(Builder $q) => $q->where('created_at', '>=', $from->format('Y-m-d')))
            ->when($to, fn(Builder $q) => $q->where('created_at', '<=', $to->format('Y-m-d')));
    }
}
