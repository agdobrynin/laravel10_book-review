<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Review;
use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DateWithRating;
use Tests\TestCase;

class BookModelTest extends TestCase
{
    use RefreshDatabase;

    public function testModelExist(): void
    {
        $book = Book::factory()->create();
        $this->assertDatabaseHas(Book::class, $book->toArray());
    }

    public function testScopeTitle(): void
    {
        Book::factory(2)->state(
            new Sequence(
                ['title' => 'more then abc book'],
                ['title' => 'The Qwe is good book']
            )
        )->create();
        /** @var Collection $books */
        $books = Book::title('qwe')->get();

        $this->instance(Collection::class, $books);
        $this->assertCount(1, $books);
        $this->assertEquals('The Qwe is good book', $books->first()->title);
    }

    public function testScopePopularWithoutDate(): void
    {
        $this->makeBooksWithReviewCreatedAt(
            ['2023-01-01', '2023-01-02', '2023-01-01',],
            ['2023-01-02', '2023-02-05',],
            ['2023-02-20', '2023-02-25', '2023-02-25', '2023-02-26', '2023-02-27',],
        );

        /** @var Collection $books */
        $books = Book::popular()->get();

        $this->assertEquals(5, $books->offsetGet(0)->reviews_count);
        $this->assertEquals(3, $books->offsetGet(1)->reviews_count);
        $this->assertEquals(2, $books->offsetGet(2)->reviews_count);
    }

    public function testScopePopularWithDateFilterOne(): void
    {
        [$book1, $book2] = $this->makeBooksWithReviewCreatedAt(
            ['2023-01-01', '2023-01-02', '2023-01-03',],
            ['2023-02-02', '2023-02-11', '2023-02-12', '2023-02-14',]
        );

        /** @var Collection $collection */
        $collection = Book::popular(
            DateTime::createFromFormat('Y-m-d', '2023-01-01'),
            DateTime::createFromFormat('Y-m-d', '2023-01-31'),
        )->get();

        $this->assertEquals(3, $collection->offsetGet(0)->reviews_count);
        $this->assertEquals($book1->title, $collection->offsetGet(0)->title);

        $this->assertEquals(0, $collection->offsetGet(1)->reviews_count);
        $this->assertEquals($book2->title, $collection->offsetGet(1)->title);
    }

    public function testScopePopularWithDateFilterTow(): void
    {
        [$book1, $book2] = $this->makeBooksWithReviewCreatedAt(
            ['2023-01-01', '2023-01-02', '2023-01-03',],
            ['2023-02-02', '2023-02-11', '2023-02-12', '2023-02-14',]
        );

        /** @var Collection $collection */
        $collection = Book::popular(
            DateTime::createFromFormat('Y-m-d', '2023-01-01'),
            DateTime::createFromFormat('Y-m-d', '2023-02-20'),
        )->get();

        $this->assertEquals(4, $collection->offsetGet(0)->reviews_count);
        $this->assertEquals($book2->title, $collection->offsetGet(0)->title);

        $this->assertEquals(3, $collection->offsetGet(1)->reviews_count);
        $this->assertEquals($book1->title, $collection->offsetGet(1)->title);
    }

    public function testScopePopularWithDateFilterThree(): void
    {
        [$book1, $book2] = $this->makeBooksWithReviewCreatedAt(
            ['2023-01-01', '2023-01-02', '2023-01-03',],
            ['2023-02-02', '2023-02-11', '2023-02-12', '2023-02-14',]
        );

        /** @var Collection $collection */
        $collection = Book::popular(
            DateTime::createFromFormat('Y-m-d', '2023-02-01'),
            DateTime::createFromFormat('Y-m-d', '2023-02-20'),
        )->get();

        $this->assertEquals(4, $collection->offsetGet(0)->reviews_count);
        $this->assertEquals($book2->title, $collection->offsetGet(0)->title);

        $this->assertEquals(0, $collection->offsetGet(1)->reviews_count);
        $this->assertEquals($book1->title, $collection->offsetGet(1)->title);
    }

    public function testScopeHiRated(): void
    {
        [$book1, $book2] = $this->makeBooksWithReviewCreatedAtWithRating(
            [
                new DateWithRating(rating:  1),
                new DateWithRating(rating:  5),
                new DateWithRating(rating:  1),
            ],
            [
                new DateWithRating(rating:  5),
                new DateWithRating(rating:  1),
                new DateWithRating(rating:  3),
            ],
        );

        $avgBook1 = round((1 + 5 + 1) / 3, 2);
        $avgBook2 = round((5 + 1 + 3) / 3, 2);

        /** @var Collection $collection */
        $collection = Book::hiRated()->get();

        $this->assertCount(2, $collection);
        $this->assertEquals($avgBook2, round($collection->offsetGet(0)->reviews_avg_rating, 2));
        $this->assertEquals($avgBook1, round($collection->offsetGet(1)->reviews_avg_rating, 2));

        $firstItem = $collection->first();
        $this->assertEquals($book2->id, $firstItem->id);
        $this->assertEquals($book2->title, $firstItem->title);
        $this->assertEquals($book2->created_at, $firstItem->created_at);
        $this->assertEquals($book2->updated_at, $firstItem->updated_at);
    }

    public function testScopeHiRatedWithDateFilter(): void
    {
        [$book1, $book2] = $this->makeBooksWithReviewCreatedAtWithRating(
            [
                new DateWithRating(1, '2023-02-01'),
                new DateWithRating(5, '2023-02-01'),
                new DateWithRating(  1, '2023-02-02'),
            ],
            [
                new DateWithRating(3, '2023-01-15'),
                new DateWithRating(2, '2023-01-20'),
                new DateWithRating(5, '2023-02-20'),
                new DateWithRating(4, '2023-02-21'),
            ],
        );

        $avgBook2 = round((3 + 2) / 2, 2);

        /** @var Collection $collection */
        $collection = Book::hiRated(
            DateTime::createFromFormat('Y-m-d', '2023-01-01'),
            DateTime::createFromFormat('Y-m-d', '2023-01-31'),
        )->get();

        $this->assertCount(2, $collection);
        $this->assertEquals($avgBook2, round($collection->offsetGet(0)->reviews_avg_rating, 2));
        $this->assertEquals(0, round($collection->offsetGet(1)->reviews_avg_rating, 2));
    }

    /**
     * @return Book[]
     */
    protected function makeBooksWithReviewCreatedAt(array ...$reviewCreatedAtDates): array
    {
        $books = [];

        foreach ($reviewCreatedAtDates as $reviewCreatedAtDate) {
            $books[] = Book::factory()->has(
                Review::factory(count($reviewCreatedAtDate))
                    ->sequence(fn(Sequence $sequence) => ['created_at' => $reviewCreatedAtDate[$sequence->index]])
            )->create();
        }

        return $books;
    }

    /**
     * @param DateWithRating[] ...$reviewCreatedAtDateWithRatings
     * @return Book[]
     */
    protected function makeBooksWithReviewCreatedAtWithRating(array ...$reviewCreatedAtDateWithRatings): array
    {
        $books = [];
        foreach ($reviewCreatedAtDateWithRatings as $reviewCreatedAtDateWithRating) {
            $books[] = Book::factory()->has(
                Review::factory(count($reviewCreatedAtDateWithRating))
                    ->sequence(fn(Sequence $sequence) => [
                        'created_at' => $reviewCreatedAtDateWithRating[$sequence->index]->date,
                        'rating' => $reviewCreatedAtDateWithRating[$sequence->index]->rating,
                    ])
            )->create();
        }

        return $books;
    }
}
