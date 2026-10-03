{{--
    THE OVRLNDR logo with INDONESIA spread across the width underneath.
    $tone: 'light' (white text, for dark backgrounds, default) or 'dark' (dark text, for light backgrounds).
    $size: Tailwind text size of the wordmark (default text-xl). Put it inside an element with the "group" class for the hover colour swap.
--}}
@php
    $dark = ($tone ?? 'light') === 'dark';
    $size = $size ?? 'text-xl';
    $mainText = $dark ? 'text-neutral-900' : 'text-white';
    $swapToMain = $dark ? 'group-hover:text-neutral-900' : 'group-hover:text-white';
    $subText = $dark ? 'text-neutral-600' : 'text-neutral-200';
@endphp
<span class="inline-flex flex-col shrink-0" aria-label="The Ovrlndr Indonesia">
    <span class="flex items-baseline gap-1 {{ $size }} font-black tracking-tight leading-none" aria-hidden="true">
        <span class="text-brand-500 transition-colors duration-300 {{ $swapToMain }}">THE</span>
        <span class="{{ $mainText }} transition-colors duration-300 group-hover:text-brand-500">OVRLNDR</span>
    </span>
    <span class="flex justify-between w-full mt-1 text-[8px] font-medium leading-none {{ $subText }}" aria-hidden="true">
        @foreach(str_split('INDONESIA') as $letter)<span>{{ $letter }}</span>@endforeach
    </span>
</span>
