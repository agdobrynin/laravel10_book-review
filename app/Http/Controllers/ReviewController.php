<?php

namespace App\Http\Controllers;

use App\Dto\ReviewDto;
use App\Http\Requests\ReviewRequest;
use App\Models\Book;
use App\Models\Review;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('throttle:store-review')
            ->only('store');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Book $book)
    {
        return view('books.reviews.create', ['book' => $book]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request, Book $book)
    {
        $dto = new ReviewDto(...$request->validated());

        $review = new Review();
        $review->content = $dto->content;
        $review->rating = $dto->rating;
        $book->reviews()->save($review);

        return redirect()->route('books.show', $book);
    }
}
