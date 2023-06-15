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
        Book::factory(30)
            ->has(
                Review::factory(
                    rand(10, 20),
                    function (array $attr, Book $book) {
                        $date = fake()->dateTimeBetween($book->created_at);

                        return [
                            'created_at' => $date,
                            'updated_at' => $date,
                        ];
                    }
                )
            )
            ->create();
    }
}
