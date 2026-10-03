<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Overlander')</title>
    @php
        $metaTitle = html_entity_decode(trim(strip_tags($__env->yieldContent('title', 'Overlander'))), ENT_QUOTES);
        $metaDescription = \Illuminate\Support\Str::limit(html_entity_decode(trim(strip_tags($__env->yieldContent('meta_description'))), ENT_QUOTES) ?: __('nav.meta_description'), 200);
        $metaImage = html_entity_decode(trim($__env->yieldContent('og_image')), ENT_QUOTES) ?: asset('storage/destinations/mount-bromo.jpg');
    @endphp
    <meta name="description" content="{{ $metaDescription }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="The Overlander Indonesia">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:image" content="{{ $metaImage }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $metaImage }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-white font-sans text-gray-800 overflow-x-hidden" style="font-family: 'Inter', sans-serif;">

    @php $adminOnly = auth()->check() && auth()->user()->isAdmin(); @endphp

    {{-- Navbar --}}
    <nav class="bg-neutral-900 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">

                <a href="{{ $adminOnly ? route('admin.dashboard') : route('home') }}" class="group flex items-baseline gap-1 shrink-0 transition-transform duration-300 hover:scale-105">
                    <span class="text-xl font-black tracking-tight text-brand-500 transition-colors duration-300 group-hover:text-white">THE</span>
                    <span class="text-xl font-black tracking-tight text-white transition-colors duration-300 group-hover:text-brand-500">OVRLNDR</span>
                </a>

                @if($adminOnly)
                    @include('layouts._nav-admin')
                @else
                @include('layouts._nav-desktop')

                <button id="nav-toggle" class="sm:hidden p-2 rounded-lg text-neutral-300 hover:bg-neutral-800 transition-colors">
                    <svg id="icon-open" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg id="icon-close" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                @endif
            </div>

            @unless($adminOnly)
                @include('layouts._nav-mobile')
            @endunless
        </div>
    </nav>

    {{-- Flash messages --}}
    @if(session('success') || session('info') || session('error'))
    <div class="max-w-6xl mx-auto px-4 mt-4 space-y-2">
        @if(session('success'))
            <div class="flash-msg bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm flex items-center justify-between">
                <span>✓ {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700 ml-4 font-bold">×</button>
            </div>
        @endif
        @if(session('info'))
            <div class="flash-msg bg-blue-50 border border-blue-200 text-blue-800 rounded-xl px-4 py-3 text-sm flex items-center justify-between">
                <span>ℹ {{ session('info') }}</span>
                <button onclick="this.parentElement.remove()" class="text-blue-500 hover:text-blue-700 ml-4 font-bold">×</button>
            </div>
        @endif
        @if(session('error'))
            <div class="flash-msg bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm flex items-center justify-between">
                <span>✕ {{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 ml-4 font-bold">×</button>
            </div>
        @endif
    </div>
    @endif

    {{-- Main content --}}
    <main class="@yield('main-class', 'max-w-6xl mx-auto px-4 py-8')">
        @yield('content')
    </main>

    @unless($adminOnly)
    <footer class="mt-16 bg-neutral-900 text-neutral-300">
        <div class="max-w-6xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
                <div>
                    <p class="text-xl font-black mb-2"><span class="text-brand-500">THE</span> <span class="text-white">OVRLNDR</span></p>
                    <p class="text-sm text-neutral-400 leading-relaxed">{{ __('nav.footer_tagline') }}</p>

                    <form method="POST" action="{{ route('newsletter.subscribe') }}" class="mt-5">
                        @csrf
                        <p class="text-xs font-bold uppercase tracking-wider text-neutral-500 mb-1">{{ __('newsletter.title') }}</p>
                        <p class="text-xs text-neutral-500 mb-2">{{ __('newsletter.subtitle') }}</p>
                        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                        <div class="flex gap-2">
                            <input type="email" name="email" required placeholder="{{ __('newsletter.placeholder') }}" aria-label="{{ __('newsletter.placeholder') }}"
                                   class="min-w-0 flex-1 bg-neutral-800 text-neutral-200 placeholder-neutral-500 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-brand-400">
                            <button type="submit" data-no-loading class="btn-pop shrink-0 px-3 py-2 bg-brand-500 text-white rounded-lg text-xs font-semibold hover:bg-brand-600 transition-colors">{{ __('newsletter.cta') }}</button>
                        </div>
                        @error('email', 'newsletter')
                            <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-neutral-500 mb-3">{{ __('nav.footer_explore') }}</p>
                    <div class="space-y-2">
                        <a href="{{ route('destinations.index') }}" class="block text-sm hover:text-brand-400 transition-colors">{{ __('nav.footer_destinations') }}</a>
                        <a href="{{ route('packages.index') }}" class="block text-sm hover:text-brand-400 transition-colors">{{ __('nav.footer_tour_packages') }}</a>
                        <a href="{{ route('articles.index') }}" class="block text-sm hover:text-brand-400 transition-colors">{{ __('nav.footer_blog') }}</a>
                        <a href="{{ route('about') }}" class="block text-sm hover:text-brand-400 transition-colors">{{ __('nav.about') }}</a>
                        <a href="{{ route('gallery') }}" class="block text-sm hover:text-brand-400 transition-colors">{{ __('nav.gallery') }}</a>
                        <a href="{{ route('faq') }}" class="block text-sm hover:text-brand-400 transition-colors">{{ __('nav.faq') }}</a>
                        <a href="{{ route('contact') }}" class="block text-sm hover:text-brand-400 transition-colors">{{ __('nav.contact') }}</a>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-neutral-500 mb-3">{{ __('nav.footer_get_in_touch') }}</p>
                    <div class="space-y-2 text-sm text-neutral-400">
                        <p><a href="https://wa.me/{{ config('booking.whatsapp_number') }}" target="_blank" rel="noopener" class="hover:text-brand-400 transition-colors">WhatsApp: +62 856-4103-4599</a></p>
                        <p><a href="mailto:{{ config('booking.email') }}" class="hover:text-brand-400 transition-colors">Email: {{ config('booking.email') }}</a></p>
                        @foreach(config('booking.socials') as $name => $social)
                            @if($social)
                            <p><a href="{{ $social['url'] }}" target="_blank" rel="noopener" class="hover:text-brand-400 transition-colors">{{ ucfirst($name) }}: {{ $social['label'] }}</a></p>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="border-t border-neutral-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-neutral-500">
                <p>&copy; {{ __('nav.footer_copyright', ['year' => date('Y')]) }}</p>
                <div class="flex items-center gap-4">
                    <a href="{{ route('privacy') }}" class="hover:text-brand-400 transition-colors">{{ __('nav.privacy') }}</a>
                    <a href="{{ route('terms') }}" class="hover:text-brand-400 transition-colors">{{ __('nav.terms') }}</a>
                </div>
            </div>
        </div>
    </footer>
    @endunless

    @unless(request()->routeIs('admin.*'))
    <a href="https://wa.me/{{ config('booking.whatsapp_number') }}?text={{ urlencode(__('nav.whatsapp_float_message')) }}"
       target="_blank" rel="noopener" aria-label="{{ __('nav.whatsapp_chat') }}" title="{{ __('nav.whatsapp_chat') }}"
       class="print:hidden fixed bottom-5 right-5 z-40 w-14 h-14 rounded-full bg-green-500 text-white shadow-lg flex items-center justify-center transition-all duration-200 hover:bg-green-600 hover:scale-110 hover:shadow-xl active:scale-95">
        <svg class="w-7 h-7" viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2c-5.46 0-9.91 4.45-9.91 9.91 0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38c1.45.79 3.08 1.21 4.79 1.21h.01c5.46 0 9.91-4.45 9.91-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0012.04 2zm0 18.07h-.01c-1.5 0-2.97-.4-4.25-1.16l-.3-.18-3.12.82.83-3.04-.2-.31a8.2 8.2 0 01-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.83 2.42a8.18 8.18 0 012.41 5.83c0 4.55-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.17.25-.64.81-.78.97-.14.17-.29.19-.53.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.38-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.85-.2-.48-.41-.42-.56-.43-.14-.01-.31-.01-.48-.01a.92.92 0 00-.67.31c-.23.25-.87.85-.87 2.07 0 1.22.89 2.4 1.01 2.57.12.17 1.75 2.67 4.25 3.74.59.26 1.06.41 1.42.52.6.19 1.14.16 1.57.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.08.14-1.18-.06-.11-.23-.17-.48-.29z"/></svg>
    </a>
    @endunless

    <script>
    const userMenuBtn      = document.getElementById('user-menu-btn');
    const userMenuDropdown = document.getElementById('user-menu-dropdown');

    userMenuBtn?.addEventListener('click', function(e) {
        e.stopPropagation();
        userMenuDropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', function() {
        userMenuDropdown?.classList.add('hidden');
    });

    const navToggle = document.getElementById('nav-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconOpen   = document.getElementById('icon-open');
    const iconClose  = document.getElementById('icon-close');

    navToggle?.addEventListener('click', function() {
        const isHidden = mobileMenu.classList.toggle('hidden');
        iconOpen.classList.toggle('hidden', !isHidden);
        iconClose.classList.toggle('hidden', isHidden);
    });

    document.addEventListener('submit', function(e) {
        const btn = e.target.querySelector('button[type="submit"]:not([data-no-loading])');
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalText = btn.innerText;
            btn.innerText = btn.dataset.loadingText || 'Saving...';
            btn.classList.add('opacity-70', 'cursor-not-allowed');
        }
    });

    document.querySelectorAll('.flash-msg').forEach(function(el) {
        setTimeout(function() {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });
    </script>

    @stack('scripts')
</body>
</html>
