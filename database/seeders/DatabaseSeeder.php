<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Book::factory(30)->create()->each(function (Book $book) {
            $date = fn() => fake()->dateTimeBetween($book->created_at);

            Review::factory(
                rand(10, 20),
                fn (array $attr) => ['created_at' => $date]
            )
                ->create(['book_id' => $book->id]);
        });
    }
}
