<x-layout :pageTitle="'Add new review for' . $book['title']">
    <div class="mb-4 flex justify-between">
        <a class="btn" href="{{ route('books.show', $book) }}">Back to book</a>
        <a class="btn" href="{{ route('books.index') }}">Books list</a>
    </div>
    <h2 class="mb-4 text-xl font-semibold">Add new review for book &laquo;{{ $book->title }}&raquo;</h2>

    <form method="post" action="{{ route('books.reviews.store', $book) }}">
        @csrf
        <div class="mb-4">
            <label for="content" class="text-slate-400 block mb-2">Review content</label>
            <textarea name="content" id="content" rows="10"
                @class(['input h-28', 'input-error' => $errors->has('content')])
                >{{ old('content') }}</textarea>
            @error('content')
            <div class="error"> {{ $message }} </div>
            @enderror
        </div>
        <div class="mb-4">
            <label for="rating" class="text-slate-400 block mb-2">Rating</label>
            <select name="rating" id="rating" @class(['input', 'input-error' => $errors->has('rating')])>
                <option value="">Select a Rating</option>
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" @if($i == old('rating')) selected @endif> {{ $i }}</option>
                @endfor
            </select>
            @error('rating')
            <div class="error"> {{ $message }} </div>
            @enderror
        </div>
        <div class="mb-4 flex justify-between">
            <div><a href="{{ route('books.show', $book) }}" class="btn h-10">Cancel</a></div>
            <div>
                <button type="submit" class="btn h-10"><span class="text-indigo-600">Save</span></button>
            </div>
        </div>
    </form>
</x-layout>
