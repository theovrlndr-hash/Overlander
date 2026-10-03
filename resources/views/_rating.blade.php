{{-- Compact star rating: $avg (float|null), $count (int), optional $dark for use over photos. Prints nothing until there is a review. --}}
@if(($count ?? 0) > 0)
    <span class="inline-flex items-center gap-1 text-xs {{ $class ?? '' }}" title="{{ number_format($avg, 1) }} / 5">
        <span class="text-yellow-400">★</span>
        <span class="font-semibold {{ ($dark ?? false) ? 'text-white' : 'text-gray-800' }}">{{ number_format($avg, 1) }}</span>
        <span class="{{ ($dark ?? false) ? 'text-neutral-300' : 'text-gray-400' }}">({{ $count }})</span>
    </span>
@endif
