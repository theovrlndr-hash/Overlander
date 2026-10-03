@extends('layouts.app')

@section('title', 'Tour Packages — Overlander')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">{{ __('packages.title') }}</h1>
        <p class="text-gray-500 mt-1">{{ __('packages.subtitle') }}</p>
    </div>

    <div class="flex flex-wrap gap-2">
        <a href="{{ route('packages.index') }}" class="category-chip px-3 py-1.5 rounded-full text-xs font-medium border {{ ! request('category') ? 'bg-brand-500 text-white border-brand-500' : 'border-gray-200 text-gray-600 hover:bg-gray-50' }}">{{ __('packages.all') }}</a>
        @foreach($categories as $cat)
            <a href="{{ route('packages.index', ['category' => $cat->slug]) }}"
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
