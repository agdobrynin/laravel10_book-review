<x-layout :pageTitle="'Book: '.$book['title']">
    <div class="mb-4">
        <a class="link" href="{{ route('books.index') }}">Back to books list</a>
        <h1 class="sticky top-0 mb-2 mt-4 text-2xl bg-gray-100 p-2 rounded-md shadow-md">{{ $book->title }}</h1>

        <div class="book-info">
            <div class="book-author mb-4 text-lg font-semibold">by {{ $book->author }}</div>
            <div class="book-rating flex items-center">
                <div class="mr-2 text-sm font-medium text-slate-700">
                    <x-star-rating :rating="$book['reviews_avg_rating']" class="text-2xl"/>
                </div>
                <span class="book-review-count text-sm text-gray-500">
                    {{ $book->reviews_count }} {{ Str::plural('review', 5) }}
                </span>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <a href="{{ route('books.reviews.create', $book) }}" class="btn link">Add a review!</a>
    </div>

    <div>
        <h2 class="mb-4 text-xl font-semibold">Reviews</h2>
        <ul>
            @forelse ($book->reviews as $review)
                <li class="book-item mb-4">
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <div>
                                <x-star-rating :rating="$review['rating']"/>
                            </div>
                            <div class="book-review-count">{{ $review->created_at->format('M j, Y') }}</div>
                        </div>
                        <p class="text-gray-700">{{ $review->content }}</p>
                    </div>
                </li>
            @empty
                <li class="mb-4">
                    <div class="empty-book-item">
                        <p class="empty-text text-lg font-semibold">No reviews yet</p>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>
</x-layout>
