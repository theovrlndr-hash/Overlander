@extends('layouts.app')

@section('title', $destination->name . ' — Overlander')
@section('meta_description', str(strip_tags($destination->description ?? ''))->limit(160))
@section('og_image', $destination->cover_photo_url)
@section('main-class', '')

@section('content')

<div class="relative h-80 sm:h-96">
    <img src="{{ $destination->cover_photo_url }}" class="w-full h-full object-cover" alt="{{ $destination->name }}">
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0 max-w-6xl mx-auto px-4 pb-6 text-white">
        <div class="flex flex-wrap gap-2 mb-2">
            @foreach($destination->categories as $cat)
                <span class="text-xs px-2 py-0.5 rounded-full bg-white/20 backdrop-blur">{{ $cat->name }}</span>
            @endforeach
        </div>
        <h1 class="text-3xl sm:text-4xl font-black">{{ $destination->name }}</h1>
        <p class="text-neutral-200 text-sm mt-1">📍 {{ $destination->location }}</p>
        @include('_rating', ['avg' => $ratingAvg ?? 0, 'count' => $ratingCount ?? 0, 'dark' => true, 'class' => 'mt-1 text-sm'])
    </div>
</div>

<div class="max-w-6xl mx-auto px-4 py-10 grid sm:grid-cols-3 gap-10">
    <div class="sm:col-span-2 space-y-8">

        @if($destination->description)
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-2">{{ __('destinations.about_title') }}</h2>
            <p class="text-gray-600 leading-relaxed">{{ $destination->description }}</p>
        </div>
        @endif

        @if($destination->what_to_do)
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-2">{{ __('destinations.what_to_do_title') }}</h2>
            <p class="text-gray-600 leading-relaxed">{{ $destination->what_to_do }}</p>
        </div>
        @endif

        @if($destination->point_of_interest)
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-2">{{ __('destinations.poi_title') }}</h2>
            <p class="text-gray-600 leading-relaxed">{{ $destination->point_of_interest }}</p>
        </div>
        @endif

        @php $tipLines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) $destination->tips)))); @endphp
        @if(count($tipLines))
        <div class="rounded-2xl border border-brand-200 bg-brand-50/50 p-5">
            <h2 class="text-lg font-bold text-gray-900 mb-3">{{ __('destinations.tips_title') }}</h2>
            <ul class="space-y-2 text-sm text-gray-700 leading-relaxed">
                @foreach($tipLines as $line)
                <li class="flex gap-2"><span class="text-brand-500 shrink-0">✓</span><span>{{ $line }}</span></li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($destination->activities->count())
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('destinations.activity_title') }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($destination->activities as $activity)
                <div class="rounded-2xl overflow-hidden border border-gray-100">
                    @if($activity->photo)
                        <img src="{{ $activity->photo_url }}" class="w-full h-32 object-cover" alt="{{ $activity->title }}">
                    @endif
                    <div class="p-4">
                        <span class="text-xs uppercase tracking-wide text-brand-500 font-semibold">{{ $activity->type }}</span>
                        <p class="font-semibold text-gray-800 mt-1">{{ $activity->title }}</p>
                        @if($activity->description)
                            <p class="text-sm text-gray-500 mt-1">{{ $activity->description }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Reviews --}}
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('destinations.reviews_title') }}</h2>

            @auth
            <form method="POST" action="{{ route('reviews.store', $destination) }}" enctype="multipart/form-data" class="bg-gray-50 rounded-2xl p-4 mb-6">
                @csrf
                <p class="text-sm font-medium text-gray-700 mb-2">{{ __('destinations.review_form_label') }}</p>
                <div class="flex gap-1 mb-3" id="rating-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer text-2xl text-gray-300 [&:has(input:checked)]:text-yellow-400 has-[~label:hover]:text-yellow-300">
                            <input type="radio" name="rating" value="{{ $i }}" class="hidden" required>★
                        </label>
                    @endfor
                </div>
                <textarea name="comment" rows="2" placeholder="{{ __('destinations.review_placeholder') }}"
                          class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm mb-3 focus:border-brand-400 focus:ring-2 focus:ring-brand-100"></textarea>
                <div class="mb-3">
                    <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('destinations.review_photos_label') }}</label>
                    <input type="file" name="photos[]" id="review-photos" accept="image/jpeg,image/png,image/webp" multiple
                           class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-600 file:font-medium hover:file:bg-brand-100 file:cursor-pointer">
                    <p class="text-[11px] text-gray-400 mt-1" id="review-photos-hint" data-max-msg="{{ __('destinations.review_photos_max') }}">{{ __('destinations.review_photos_hint') }}</p>
                    @error('photos') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    @error('photos.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="btn-pop px-5 py-2 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600">{{ __('destinations.review_submit') }}</button>
            </form>
            @else
            <p class="text-sm text-gray-500 mb-6">
                <a href="{{ route('login') }}" class="text-brand-500 font-medium hover:underline">{{ __('destinations.login') }}</a> {{ __('destinations.to_leave_a_review') }}
            </p>
            @endauth

            <div class="space-y-4">
                @forelse($reviews as $review)
                <div class="flex items-start gap-3 border-b border-gray-100 pb-4">
                    <div class="w-9 h-9 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center text-xs font-bold shrink-0">
                        {{ strtoupper(substr($review->reviewer_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-medium text-sm text-gray-800">{{ $review->reviewer_name }}</span>
                            <span class="text-yellow-500 text-xs">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
                        @include('_review-photos', ['review' => $review])
                    </div>
                </div>
                @empty
                <p class="text-sm text-gray-400">{{ __('destinations.reviews_empty') }}</p>
                @endforelse
            </div>
            {{ $reviews->links() }}
        </div>
    </div>

    <div class="space-y-6">
        @if($destination->nature_level || $destination->culture_level || $destination->heritage_level)
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="font-semibold text-gray-800 mb-3">{{ __('destinations.levels_title') }}</p>
            @foreach(['nature_level' => __('destinations.level_nature'), 'culture_level' => __('destinations.level_culture'), 'heritage_level' => __('destinations.level_heritage')] as $field => $label)
                @if($destination->$field)
                <div class="mb-3 last:mb-0">
                    <div class="flex justify-between text-xs text-gray-500 mb-1"><span>{{ $label }}</span><span>{{ $destination->$field }}/5</span></div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-brand-500" style="width: {{ $destination->$field * 20 }}%"></div>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
        @endif

        @if($destination->packages->count())
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="font-semibold text-gray-800 mb-3">{{ __('destinations.available_packages_title') }}</p>
            <div class="space-y-3">
                @foreach($destination->packages as $package)
                <a href="{{ route('packages.show', $package) }}" class="block border border-gray-100 rounded-xl p-3 hover:border-brand-300 transition-colors">
                    <p class="text-sm font-medium text-gray-800">{{ $package->name }}</p>
                    <p class="text-xs text-gray-500">{{ $package->category?->name }}</p>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($destination->packages->isNotEmpty())
        <a href="{{ route('packages.show', $destination->packages->first()) }}"
           class="btn-pop block text-center px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
            {{ __('destinations.book_now') }}
        </a>
        @endif
    </div>
</div>

@push('scripts')
<script>
(function () {
    const input = document.getElementById('review-photos');
    const hint = document.getElementById('review-photos-hint');
    if (! input) return;
    const original = hint.textContent;

    input.addEventListener('change', () => {
        if (input.files.length > 3) {
            input.value = '';
            hint.textContent = hint.dataset.maxMsg;
            hint.classList.add('text-red-500');
        } else {
            hint.textContent = original;
            hint.classList.remove('text-red-500');
        }
    });
})();
</script>
@endpush

@endsection
