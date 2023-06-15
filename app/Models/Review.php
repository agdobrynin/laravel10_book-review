<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = ['content', 'rating'];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
