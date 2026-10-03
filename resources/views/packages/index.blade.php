@extends('layouts.app')

@section('title', 'Tour Packages — Overlander')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ __('packages.title') }}</h1>
        <p class="text-gray-500 mt-1">{{ __('packages.subtitle') }}</p>
    </div>

    @php $filtersActive = request()->filled('duration') || request()->filled('destination') || request()->filled('sort'); @endphp
    <form method="GET" action="{{ route('packages.index') }}" class="flex flex-wrap items-end gap-3 bg-white rounded-2xl border border-gray-200 p-4">
        @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif

        <div class="min-w-36">
            <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('packages.filter_duration') }}</label>
            <select name="duration" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                <option value="">{{ __('packages.filter_any') }}</option>
                @foreach($durationOptions as $opt)
                    <option value="{{ $opt }}" @selected(request('duration') === $opt)>{{ __('packages.duration_' . ($opt === '7+' ? '7plus' : str_replace('-', '_', $opt))) }}</option>
                @endforeach
            </select>
        </div>

        <div class="min-w-44">
            <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('packages.filter_destination') }}</label>
            <select name="destination" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                <option value="">{{ __('packages.filter_any') }}</option>
                @foreach($destinationOptions as $dest)
                    <option value="{{ $dest->id }}" @selected((string) request('destination') === (string) $dest->id)>{{ $dest->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="min-w-40">
            <label class="block text-xs font-medium text-gray-500 mb-1">{{ __('packages.filter_sort') }}</label>
            <select name="sort" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-xl px-3 py-2 text-sm focus:border-brand-400 focus:ring-2 focus:ring-brand-100">
                @foreach(['' => 'sort_recommended', 'shortest' => 'sort_shortest', 'longest' => 'sort_longest', 'newest' => 'sort_newest'] as $value => $key)
                    <option value="{{ $value }}" @selected((string) request('sort') === (string) $value)>{{ __('packages.' . $key) }}</option>
                @endforeach
            </select>
        </div>

        @if($filtersActive)
            <a href="{{ route('packages.index', array_filter(['category' => request('category')])) }}" class="px-3 py-2 text-sm text-gray-500 hover:text-brand-500">{{ __('packages.filter_reset') }}</a>
        @endif
        <noscript><button type="submit" class="px-4 py-2 bg-brand-500 text-white text-sm font-medium rounded-xl">OK</button></noscript>
    </form>

    @php $keep = request()->only(['duration', 'destination', 'sort']); @endphp
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('packages.index', $keep) }}" class="category-chip px-3 py-1.5 rounded-full text-xs font-medium border {{ ! request('category') ? 'bg-brand-500 text-white border-brand-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">{{ __('packages.all') }}</a>
        @foreach($categories as $cat)
            <a href="{{ route('packages.index', array_merge($keep, ['category' => $cat->slug])) }}"
               class="category-chip px-3 py-1.5 rounded-full text-xs font-medium border
               {{ request('category') === $cat->slug ? 'bg-brand-500 text-white border-brand-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                {{ $cat->name }}
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        @forelse($packages as $package)
        <div class="relative">
            <a href="{{ route('packages.show', $package) }}" class="block rounded-2xl overflow-hidden border border-gray-100 provider-card">
                <img src="{{ $package->cover_photo_url }}" class="w-full h-44 object-cover" alt="{{ $package->name }}">
                <div class="p-4">
                    <span class="text-xs px-2 py-0.5 rounded-full bg-brand-50 text-brand-600">{{ $package->category?->name }}</span>
                    @if($package->duration_days)
                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 ml-1">{{ trans_choice('packages.trip_facts_duration_value', $package->duration_days, ['n' => $package->duration_days]) }}</span>
                    @endif
                    <p class="font-semibold text-gray-800 mt-2 line-clamp-2">{{ $package->name }}</p>
                    @include('_rating', ['avg' => $ratings[$package->id]['avg'] ?? 0, 'count' => $ratings[$package->id]['count'] ?? 0, 'class' => 'mt-1'])
                </div>
            </a>
            @include('_wishlist-heart', ['package' => $package, 'position' => 'absolute top-3 right-3 z-10'])
        </div>
        @empty
        <p class="col-span-3 text-center text-gray-400 py-12">{{ __('packages.empty') }}</p>
        @endforelse
    </div>

    {{ $packages->links() }}

    <div class="bg-gray-50 rounded-2xl p-6 text-center">
        <p class="font-semibold text-gray-800 mb-1">{{ __('packages.custom_title') }}</p>
        <p class="text-sm text-gray-500 mb-4">{{ __('packages.custom_subtitle') }}</p>
        @auth
            <a href="{{ route('bookings.create-custom') }}" class="btn-pop inline-block px-6 py-2.5 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600">{{ __('packages.custom_cta') }}</a>
        @else
            <a href="{{ route('login') }}" class="btn-pop inline-block px-6 py-2.5 bg-brand-500 text-white rounded-xl text-sm font-medium hover:bg-brand-600">{{ __('packages.custom_cta_guest') }}</a>
        @endauth
    </div>
</div>
@endsection
