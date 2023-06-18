<?php

namespace App\Http\Controllers;

use App\Contracts\CacheBookFilterInterface;
use App\Contracts\CacheBookWithReviewsInterface;
use App\Dto\BookFilterDto;
use App\Enums\BookFilterEnum;
use App\Http\Requests\BookFilterRequest;
use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(BookFilterRequest $request, CacheBookFilterInterface $cacheBookFilter)
    {
        $filterDto = new BookFilterDto(...$request->validated());

        $books = Book::when(
            $filterDto->title,
            fn(Builder $builder, $value) => $builder->title($value)
        );

        $books = match ($filterDto->filter) {
            BookFilterEnum::popularLastMonth->name => $books->popularLastMonth(),
            BookFilterEnum::popularLastSixMonths->name => $books->popularLast6Months(),
            BookFilterEnum::hiRatedLastMonth->name => $books->hiRatedLastMonth(),
            BookFilterEnum::hiRatedLastSixMonths->name => $books->hiRatedLast6Months(),
            default => $books->latest()->withReviewsCount()->withReviewsAvgRating(),
        };

        $result = $cacheBookFilter->get($filterDto, $books);

        return view('books.index', ['books' => $result]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, CacheBookWithReviewsInterface $cacheBookWithReviews)
    {
        return view('books.show', ['book' => $cacheBookWithReviews->get($id)]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
    }
}
