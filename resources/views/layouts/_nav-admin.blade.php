<div class="flex items-center gap-4 text-sm">
    <span class="hidden sm:inline text-neutral-400">{{ auth()->user()->name }}</span>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" data-no-loading class="text-red-400 hover:text-red-300 transition-colors font-medium">{{ __('nav.log_out') }}</button>
    </form>
</div>
