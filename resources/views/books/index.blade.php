<x-layout pageTitle="List of books with filters.">
    <h1 class="mb-10 text-2xl">Books with reviews</h1>
    <div class="mb-4">
        <form method="GET" action="{{ route('books.index') }}" class="flex items-center space-x-4">
            <input type="text" name="title" placeholder="Search by title" value="{{ request('title') }}"
                @class(['input h-10', 'input-error' => $errors->has('title')])/>
            <input type="hidden" name="filter" value="{{ request('filter') }}"/>
            <button type="submit" class="btn h-10">Search</button>
            <a href="{{ route('books.index') }}" class="btn h-10">Clear</a>
        </form>
        @error('title')
        <div class="error"> {{ $message }} </div>
        @enderror
        <div class="mt-4">
            <div @class(['filter-container flex' ,'input-error' => $errors->has('filter')])>
                <a href="{{ route('books.index', [...request()->query(), 'filter' => '']) }}"
                   class="{{ (request('filter') === null) ? 'filter-item-active' : 'filter-item' }}">
                    Latest
                </a>
                @foreach (\App\Enums\BookFilterEnum::cases() as $case)
                    <a href="{{ route('books.index', [...request()->query(), 'filter' => $case->name]) }}"
                       class="{{ request('filter') === $case->name ? 'filter-item-active' : 'filter-item' }}">
                        {{ $case }}
                    </a>
                @endforeach
            </div>
            @error('filter')
            <div class="error">{{ $message }}/div>
                @enderror
            </div>
        </div>
    </div>
    <ul>
        @forelse($books as $book)
            <li class="mb-4">
                <div class="book-item">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full flex-grow sm:w-auto">
                            <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                            <div class="book-author">by {{ $book->author }}</div>
                            <div class="book-review-count">created at {{ $book->created_at->format('M j, Y') }}</div>
                        </div>
                        <div>
                            <div class="book-rating">
                                <x-star-rating :rating="$book['reviews_avg_rating']" class="text-2xl"/>
                            </div>
                            <div class="book-review-count">
                                out of {{ number_format($book->reviews_count, 0) }} {{ Str::plural('review', $book->reviews_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item">
                    <p class="empty-text">No books found</p>
                    <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
                </div>
            </li>
        @endforelse
    </ul>
</x-layout>
