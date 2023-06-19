<?php

namespace App\Http\Controllers;

use App\Dto\ReviewDto;
use App\Http\Requests\ReviewRequest;
use App\Models\Book;
use App\Models\Review;
use App\Services\ReviewStoreRateLimit;

class ReviewController extends Controller
{
    public function __construct()
    {
        // Max request per minute for store method (with validation request)
        $this->middleware(['throttle:6,1'])->only(['store']);
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
    public function store(ReviewRequest $request, Book $book, ReviewStoreRateLimit $reviewStoreRateLimit)
    {
        $dto = new ReviewDto(...$request->validated());

        try {
            $reviewStoreRateLimit->checkLimit($book, 1);
        } catch (\LogicException $exception) {
            abort(429, $exception->getMessage());
        }

        $review = new Review();
        $review->content = $dto->content;
        $review->rating = $dto->rating;
        $book->reviews()->save($review);

        return redirect()->route('books.show', $book);
    }
}
