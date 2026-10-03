{{-- Thumbnails of a review's photos; each opens the full picture in a new tab. --}}
@if($review->photos->isNotEmpty())
    <div class="flex flex-wrap gap-2 mt-2">
        @foreach($review->photos as $photo)
            <a href="{{ $photo->url }}" target="_blank" rel="noopener">
                <img src="{{ $photo->url }}" alt="" loading="lazy"
                     class="w-20 h-20 object-cover rounded-lg border border-gray-200 transition-transform duration-200 hover:scale-105">
            </a>
        @endforeach
    </div>
@endif
