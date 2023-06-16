<x-layout>
    @forelse($books as $book)
        <div>{{ $book->title }}</div>
    @empty
        <div>Books not found.</div>
    @endforelse
</x-layout>
