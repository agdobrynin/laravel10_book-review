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

    public function scopePopular(Builder $builder, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): Builder
    {
        return $builder->withCount(['reviews' => fn(Builder $q) => $this->dateFilter($q, $from, $to)])
            ->orderBy('reviews_count', 'desc');
    }

    public function scopeHiRated(Builder $builder, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null): Builder
    {
        return $builder->withAvg(['reviews' => fn(Builder $q) => $this->dateFilter($q, $from, $to)], 'rating')
            ->orderBy('reviews_avg_rating', 'desc');
    }

    private function dateFilter(Builder $builder, ?DateTimeInterface $from = null, ?DateTimeInterface $to = null)
    {
        return $builder
            ->when($from, fn(Builder $q) => $q->where('created_at', '>=', $from->format('Y-m-d')))
            ->when($to, fn(Builder $q) => $q->where('created_at', '<=', $to->format('Y-m-d')));
    }
}
