<span {{ $attributes->merge(['class' => 'cursor-pointer']) }} title="Rating is {{ round($rating, 2) }}">
@if ($rating)
    @for($i = 1; $i <= 5; $i++ )
            {{ $i <= round($rating) ? '★': '☆' }}
    @endfor
@else
    No rating yet.
@endif
</span>
