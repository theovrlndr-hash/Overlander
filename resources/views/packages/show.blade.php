@extends('layouts.app')

@section('title', $package->name . ' — Overlander')
@section('meta_description', str(strip_tags($package->description ?? ''))->limit(160))
@section('og_image', $package->cover_photo_url)
@section('main-class', '')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
@endpush

@section('content')

@php
    $galleryPhotos = $package->galleryPhotoUrls();
@endphp
<div class="relative h-72 sm:h-96 overflow-hidden" id="package-gallery">
    @foreach($galleryPhotos as $i => $photo)
    <img src="{{ $photo }}" class="package-gallery-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-500 {{ $i === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}" alt="{{ $package->name }}">
    @endforeach
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
    @include('_wishlist-heart', ['package' => $package, 'position' => 'absolute top-4 left-4 z-20'])
    <div class="absolute bottom-0 left-0 right-0 max-w-6xl mx-auto px-4 pb-6 text-white">
        @if($package->category)
            <span class="text-xs px-2 py-0.5 rounded-full bg-white/20 backdrop-blur">{{ $package->category->name }}</span>
        @endif
        <h1 class="text-3xl sm:text-4xl font-black mt-2">{{ $package->name }}</h1>
        @if($package->destinations->count() > 1)
            <p class="text-neutral-200 text-sm mt-1">📍 {{ $package->destinations->pluck('name')->join(' → ') }}</p>
        @endif
        @include('_rating', ['avg' => $rating['avg'] ?? 0, 'count' => $rating['count'] ?? 0, 'dark' => true, 'class' => 'mt-1 text-sm'])
    </div>

    @if(count($galleryPhotos) > 1)
    <button type="button" id="gallery-prev" aria-label="Previous photo"
            class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center transition-all duration-200 hover:scale-110">
        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
    </button>
    <button type="button" id="gallery-next" aria-label="Next photo"
            class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-white/10 hover:bg-white/20 backdrop-blur flex items-center justify-center transition-all duration-200 hover:scale-110">
        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
    </button>
    <div class="absolute top-4 right-4 flex gap-1.5 z-20">
        @foreach($galleryPhotos as $i => $photo)
        <button type="button" class="gallery-dot w-1.5 h-1.5 rounded-full transition-colors {{ $i === 0 ? 'bg-white' : 'bg-white/40' }}" data-dot="{{ $i }}" aria-label="Go to photo {{ $i + 1 }}"></button>
        @endforeach
    </div>
    @endif
</div>

<div class="max-w-6xl mx-auto px-4 py-10">

    @if($package->duration_days || $package->start_city || $package->end_city)
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 mb-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-5">
        @if($package->duration_days)
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="5" width="18" height="16" rx="2"/>
                <path d="M3 10h18M8 3v4M16 3v4"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_duration') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ trans_choice('packages.trip_facts_duration_value', $package->duration_days, ['n' => $package->duration_days]) }}</p>
            </div>
        </div>
        @endif
        @if($package->start_city)
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 21s7-6.5 7-12a7 7 0 10-14 0c0 5.5 7 12 7 12z"/>
                <circle cx="12" cy="9" r="2.5"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_start') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ $package->start_city }}</p>
            </div>
        </div>
        @endif
        @if($package->end_city)
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 21V4l14 7-14 7"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_end') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ $package->end_city }}</p>
            </div>
        </div>
        @endif
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 11l1.5-4.5A2 2 0 018.4 5h7.2a2 2 0 011.9 1.5L19 11"/>
                <rect x="3" y="11" width="18" height="6" rx="1.5"/>
                <circle cx="7" cy="19" r="1.3"/>
                <circle cx="17" cy="19" r="1.3"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_transportation') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ __('packages.trip_facts_transportation_value') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M7 3v6a2 2 0 002 2v10M7 3a2 2 0 00-2 2v4M11 3v8M17 3c-1.5 0-2.5 1.5-2.5 4s1 4 2.5 4v10"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_meals') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ $package->hasMealsOption() ? __('packages.trip_facts_included') : __('packages.trip_facts_not_included') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 21V9l8-5 8 5v12"/>
                <path d="M9 21v-6h6v6"/>
            </svg>
            <div>
                <p class="text-xs text-gray-400">{{ __('packages.trip_facts_accommodation') }}</p>
                <p class="text-sm font-semibold text-gray-800">{{ $package->hasAccommodationOption() ? __('packages.trip_facts_included') : __('packages.trip_facts_not_included') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="grid sm:grid-cols-3 gap-10">
    <div class="sm:col-span-2 space-y-8">

        @include('_share', ['title' => $package->name . ' — Overlander'])

        @if($package->description)
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-2">{{ __('packages.about_title') }}</h2>
            <p class="text-gray-600 leading-relaxed">{{ $package->description }}</p>
        </div>
        @endif

        @php
            $routePoints = $package->destinations->map(fn ($d) => [
                'lat' => $d->latitude ? (float) $d->latitude : null,
                'lng' => $d->longitude ? (float) $d->longitude : null,
                'name' => $d->name,
            ])->filter(fn ($p) => $p['lat'] && $p['lng'])->values();
        @endphp

        @if($package->destinations->count() >= 1)
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('packages.trip_route_title') }}</h2>
            @if($package->destinations->count() > 1)
            <div class="flex flex-wrap items-center gap-2 mb-4">
                @foreach($package->destinations as $i => $dest)
                    <a href="{{ route('destinations.show', $dest) }}" class="px-3 py-1.5 rounded-full bg-brand-50 text-brand-600 text-sm font-medium hover:bg-brand-100">{{ $dest->name }}</a>
                    @if(! $loop->last)<span class="text-gray-300">→</span>@endif
                @endforeach
            </div>
            @endif
            @if($routePoints->isNotEmpty())
            <div id="route-map" class="rounded-2xl overflow-hidden border border-gray-200" style="height: 320px;"></div>
            @endif
        </div>
        @endif

        @php
            $guideStops = $package->destinations->filter(fn ($d) => $d->best_months || $d->best_time || $d->packing || $d->provided || $d->safety);
        @endphp
        @if($guideStops->isNotEmpty())
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-1">{{ __('guide.before_you_go_title') }}</h2>
            <p class="text-sm text-gray-500 mb-4">{{ __('guide.before_you_go_hint') }}</p>
            <div class="space-y-3">
                @foreach($guideStops as $stop)
                <details class="group rounded-2xl border border-gray-200 bg-gray-50/60 open:bg-white" @if($loop->first) open @endif>
                    <summary class="flex items-center justify-between gap-3 cursor-pointer list-none px-5 py-4 font-semibold text-gray-900">
                        <span><span class="text-brand-500 mr-1">{{ $loop->iteration }}.</span> {{ $stop->name }}</span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <div class="px-5 pb-5">
                        @include('destinations._guide', ['destination' => $stop])
                    </div>
                </details>
                @endforeach
            </div>
        </div>
        @endif

        @if($package->itineraries->count())
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('packages.itinerary_title') }}</h2>
            <div class="space-y-4">
                @foreach($package->itineraries as $day)
                <div class="flex gap-4">
                    <div class="shrink-0 w-20 text-brand-500 font-bold text-sm pt-0.5">{{ $day->day_label }}</div>
                    <div class="border-l-2 border-brand-100 pl-4 pb-4 flex-1">
                        <div class="flex items-start gap-4">
                            <p class="text-gray-600 text-sm leading-relaxed flex-1">{{ $day->description }}</p>
                            @if($day->photo)
                            <img src="{{ asset('storage/' . $day->photo) }}" class="w-20 h-20 rounded-xl object-cover shrink-0" alt="{{ $day->day_label }}">
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @php
            $featureRows = $package->plans->pluck('features')->flatten()->filter()->unique()->values();
        @endphp
        @if($featureRows->isNotEmpty())
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">{{ __('packages.included_title') }}</h2>
            <div class="overflow-x-auto rounded-2xl border border-gray-200">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3 font-semibold text-gray-500">{{ __('packages.included_feature') }}</th>
                            @foreach($package->plans as $plan)
                                <th class="px-4 py-3 text-center font-semibold text-gray-800">{{ $plan->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($featureRows as $feature)
                        <tr class="transition-colors hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">{{ $feature }}</td>
                            @foreach($package->plans as $plan)
                                <td class="px-4 py-3 text-center">
                                    @if(in_array($feature, $plan->features ?? [], true))
                                        <span class="text-green-600 font-bold">✓</span>
                                    @else
                                        <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">
            <h2 class="text-lg font-bold text-gray-900 mb-3">{{ __('packages.cancellation_title') }}</h2>
            <ul class="space-y-2 text-sm text-gray-600">
                @foreach(['cancellation_1', 'cancellation_2', 'cancellation_3'] as $key)
                <li class="flex gap-2"><span class="text-brand-500 shrink-0">•</span><span>{{ __('packages.' . $key) }}</span></li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="text-sm font-medium text-gray-700 mb-3">{{ __('packages.check_availability_label') }}</p>

            <div id="availability-calendar" data-url="{{ route('packages.availability.month', $package) }}" data-min="{{ now()->addDay()->toDateString() }}">
                <div class="flex items-center justify-between mb-2">
                    <button type="button" id="cal-prev" aria-label="{{ __('packages.calendar_prev') }}"
                            class="w-8 h-8 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors disabled:opacity-30 disabled:hover:bg-transparent">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
                    </button>
                    <p id="cal-title" class="text-sm font-semibold text-gray-800"></p>
                    <button type="button" id="cal-next" aria-label="{{ __('packages.calendar_next') }}"
                            class="w-8 h-8 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-100 transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    </button>
                </div>
                <div id="cal-weekdays" class="grid grid-cols-7 text-center text-[11px] font-medium text-gray-400 mb-1"></div>
                <div id="cal-grid" class="grid grid-cols-7 gap-1"></div>

                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-3 text-[11px] text-gray-500">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>{{ __('packages.calendar_available') }}</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>{{ __('packages.calendar_limited') }}</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>{{ __('packages.calendar_full') }}</span>
                </div>
            </div>

            <p id="availability-preview-msg" class="text-xs mt-3 text-gray-500">{{ __('packages.calendar_hint') }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 p-5">
            <p class="font-semibold text-gray-800 mb-3">{{ __('packages.choose_plan_title') }}</p>

            @auth
            <form method="POST" action="{{ route('cart.store', $package) }}">
                @csrf
                <input type="hidden" name="trip_date" data-calendar-field value="">
                <div class="space-y-3">
                    @forelse($package->plans as $plan)
                    <label class="block border rounded-xl p-3 relative cursor-pointer transition-all duration-200 hover:border-brand-300 hover:shadow-md {{ $plan->is_recommended ? 'border-brand-400 bg-brand-50' : 'border-gray-200' }}">
                        @if($plan->is_recommended)
                            <span class="absolute -top-2 right-3 text-xs bg-brand-500 text-white px-2 py-0.5 rounded-full">{{ __('packages.recommended') }}</span>
                        @endif
                        <div class="flex items-start gap-2">
                            <input type="radio" name="package_plan_id" value="{{ $plan->id }}" class="mt-1" @checked($plan->is_recommended) required>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $plan->name }}</p>
                                @if($plan->features)
                                <ul class="text-xs text-gray-500 mt-2 space-y-0.5">
                                    @foreach($plan->features as $f)
                                        <li>✓ {{ $f }}</li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        </div>
                    </label>
                    @empty
                    <p class="text-sm text-gray-400">{{ __('packages.no_pricing') }}</p>
                    @endforelse
                </div>

                @if($package->plans->count())
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('packages.travelers_label') }}</label>
                    <input type="number" name="pax" min="1" max="{{ $package->capacity ?? 20 }}" value="1" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                </div>

                <button type="submit" class="btn-pop block w-full text-center mt-4 px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
                    {{ __('packages.add_to_cart') }}
                </button>
                @endif
            </form>
            @else
                <div class="space-y-3">
                    @forelse($package->plans as $plan)
                    <div class="border rounded-xl p-3 relative {{ $plan->is_recommended ? 'border-brand-400 bg-brand-50' : 'border-gray-200' }}">
                        @if($plan->is_recommended)
                            <span class="absolute -top-2 right-3 text-xs bg-brand-500 text-white px-2 py-0.5 rounded-full">{{ __('packages.recommended') }}</span>
                        @endif
                        <p class="font-semibold text-gray-800 text-sm">{{ $plan->name }}</p>
                        @if($plan->features)
                        <ul class="text-xs text-gray-500 mt-2 space-y-0.5">
                            @foreach($plan->features as $f)
                                <li>✓ {{ $f }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @empty
                    <p class="text-sm text-gray-400">{{ __('packages.no_pricing') }}</p>
                    @endforelse
                </div>

                <a href="{{ route('login') }}"
                   class="btn-pop block text-center mt-4 px-6 py-3 bg-brand-500 text-white rounded-xl font-semibold hover:bg-brand-600 transition-colors">
                    {{ __('packages.login_to_book') }}
                </a>
            @endauth
        </div>
    </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const root = document.getElementById('package-gallery');
    if (! root) return;

    const slides = Array.from(root.querySelectorAll('.package-gallery-slide'));
    const dots = Array.from(root.querySelectorAll('.gallery-dot'));
    const prevBtn = document.getElementById('gallery-prev');
    const nextBtn = document.getElementById('gallery-next');
    if (slides.length < 2) return;

    let current = 0;

    function show(index) {
        current = (index + slides.length) % slides.length;
        slides.forEach((slide, i) => {
            slide.classList.toggle('opacity-100', i === current);
            slide.classList.toggle('z-10', i === current);
            slide.classList.toggle('opacity-0', i !== current);
            slide.classList.toggle('z-0', i !== current);
        });
        dots.forEach((dot, i) => {
            dot.classList.toggle('bg-white', i === current);
            dot.classList.toggle('bg-white/40', i !== current);
        });
    }

    prevBtn?.addEventListener('click', () => show(current - 1));
    nextBtn?.addEventListener('click', () => show(current + 1));
    dots.forEach((dot, i) => dot.addEventListener('click', () => show(i)));

    let touchStartX = null;
    root.addEventListener('touchstart', (e) => { touchStartX = e.touches[0].clientX; }, { passive: true });
    root.addEventListener('touchend', (e) => {
        if (touchStartX === null) return;
        const diff = e.changedTouches[0].clientX - touchStartX;
        if (Math.abs(diff) > 40) { diff > 0 ? show(current - 1) : show(current + 1); }
        touchStartX = null;
    }, { passive: true });
})();
</script>
@endpush

@push('scripts')
<script>
(function () {
    const root = document.getElementById('availability-calendar');
    if (! root) return;

    const grid = document.getElementById('cal-grid');
    const weekdaysEl = document.getElementById('cal-weekdays');
    const titleEl = document.getElementById('cal-title');
    const prevBtn = document.getElementById('cal-prev');
    const nextBtn = document.getElementById('cal-next');
    const msgEl = document.getElementById('availability-preview-msg');
    const dateFields = document.querySelectorAll('input[name="trip_date"][data-calendar-field]');

    const monthUrl = root.dataset.url;
    const minDate = root.dataset.min;
    const locale = document.documentElement.lang || 'en';
    const i18n = {
        fullyBooked: @json(__('booking.fully_booked_js')),
        slotsLeft: @json(__('booking.slots_left_js')),
        noLimit: @json(__('packages.availability_no_limit_js')),
        selected: @json(__('packages.calendar_selected')),
    };

    const pad = n => String(n).padStart(2, '0');
    const iso = (y, m, d) => `${y}-${pad(m + 1)}-${pad(d)}`;
    const minParts = minDate.split('-').map(Number);
    const minMonth = new Date(minParts[0], minParts[1] - 1, 1);

    let view = new Date(minMonth);
    let selected = null;
    const cache = {};

    // Monday-first weekday header, localized.
    const monday = new Date(2024, 0, 1);
    for (let i = 0; i < 7; i++) {
        const d = new Date(monday);
        d.setDate(monday.getDate() + i);
        const cell = document.createElement('div');
        cell.textContent = d.toLocaleDateString(locale, { weekday: 'short' }).slice(0, 3);
        weekdaysEl.appendChild(cell);
    }

    function loadMonth(date) {
        const key = `${date.getFullYear()}-${pad(date.getMonth() + 1)}`;
        if (cache[key]) return Promise.resolve(cache[key]);

        return fetch(`${monthUrl}?month=${key}`)
            .then(res => res.json())
            .then(data => (cache[key] = data))
            .catch(() => ({ capacity: null, days: {} }));
    }

    function render(data) {
        const y = view.getFullYear();
        const m = view.getMonth();
        titleEl.textContent = view.toLocaleDateString(locale, { month: 'long', year: 'numeric' });
        prevBtn.disabled = view <= minMonth;
        grid.innerHTML = '';

        const offset = (new Date(y, m, 1).getDay() + 6) % 7;
        for (let i = 0; i < offset; i++) grid.appendChild(document.createElement('div'));

        const daysInMonth = new Date(y, m + 1, 0).getDate();
        for (let d = 1; d <= daysInMonth; d++) {
            const date = iso(y, m, d);
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = d;

            const remaining = data.capacity === null ? null : (date in data.days ? data.days[date] : data.capacity);
            const isPast = date < minDate;
            const isFull = remaining !== null && remaining <= 0;
            const isLimited = remaining !== null && remaining > 0 && remaining <= Math.max(1, Math.ceil(data.capacity * 0.25));

            let cls = 'relative h-8 rounded-lg text-xs font-medium transition-colors ';
            if (isPast || isFull) {
                cls += isFull && ! isPast ? 'bg-red-50 text-red-400 cursor-not-allowed line-through' : 'text-gray-300 cursor-not-allowed';
                btn.disabled = true;
            } else if (date === selected) {
                cls += 'bg-brand-500 text-white';
            } else {
                cls += isLimited ? 'bg-amber-50 text-amber-700 hover:bg-amber-100' : 'text-gray-700 hover:bg-brand-50';
            }
            btn.className = cls;

            if (! isPast && ! isFull && ! (date === selected)) {
                const dot = document.createElement('span');
                dot.className = 'absolute bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full ' + (isLimited ? 'bg-amber-400' : 'bg-green-500');
                btn.appendChild(dot);
            }

            btn.addEventListener('click', () => select(date, remaining));
            grid.appendChild(btn);
        }
    }

    function select(date, remaining) {
        selected = date;
        dateFields.forEach(f => (f.value = date));

        if (remaining === null) {
            msgEl.textContent = i18n.noLimit;
            msgEl.className = 'text-xs mt-3 text-green-600';
        } else {
            msgEl.textContent = i18n.slotsLeft.replace(':n', remaining);
            msgEl.className = 'text-xs mt-3 text-green-600';
        }

        loadMonth(view).then(render);
    }

    function show() {
        loadMonth(view).then(render);
    }

    prevBtn.addEventListener('click', () => { view = new Date(view.getFullYear(), view.getMonth() - 1, 1); show(); });
    nextBtn.addEventListener('click', () => { view = new Date(view.getFullYear(), view.getMonth() + 1, 1); show(); });

    show();
})();
</script>
@endpush

@if($routePoints->isNotEmpty())
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function () {
    const points = @json($routePoints);
    const mapEl = document.getElementById('route-map');
    if (! mapEl || ! points.length) return;

    const map = L.map('route-map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);

    const latLngs = points.map(p => [p.lat, p.lng]);

    points.forEach((p, i) => {
        // Numbered pins so the visiting order reads at a glance.
        const icon = L.divIcon({
            className: '',
            html: `<div style="width:28px;height:28px;border-radius:9999px;background:#f9530f;color:#fff;font:700 13px/28px Inter,sans-serif;text-align:center;border:2px solid #fff;box-shadow:0 1px 4px rgba(0,0,0,.4)">${i + 1}</div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 14],
            popupAnchor: [0, -14],
        });
        L.marker([p.lat, p.lng], { icon })
            .addTo(map)
            .bindPopup(`${i + 1}. ${p.name}`);
    });

    if (latLngs.length > 1) {
        L.polyline(latLngs, { color: '#f9530f', weight: 3, dashArray: '6 6' }).addTo(map);
        map.fitBounds(latLngs, { padding: [30, 30] });
    } else {
        map.setView(latLngs[0], 11);
    }
})();
</script>
@endpush
@endif

@endsection
