@php
    $items = [
        ['home', 'Home', '<path d="M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z" fill="currentColor"/>'],
        ['sermons', 'Sermons', '<path d="M3 7a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v3l5-3v10l-5-3v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" fill="currentColor"/>'],
        ['events', 'Events', '<path d="M7 2v3M17 2v3M4 8h16M5 4h14a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'],
        ['give', 'Give', '<path d="M12 21s-8-5.3-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.7-8 11-8 11z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>'],
        ['more', 'More', '<path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>'],
    ];
@endphp
<nav class="navbar">
    @foreach ($items as [$route, $label, $icon])
        @if ($route === 'more')
            <a href="#" @click.prevent="more = !more" :class="{ active: more }">
                <svg viewBox="0 0 24 24">{!! $icon !!}</svg>
                <span>{{ $label }}</span>
            </a>
        @else
            <a href="{{ route($route) }}" wire:navigate @click="more = false" @class(['active' => request()->routeIs($route)]) :class="{ active: !more && {{ request()->routeIs($route) ? 'true' : 'false' }} }">
                <svg viewBox="0 0 24 24">{!! $icon !!}</svg>
                <span>{{ $label }}</span>
            </a>
        @endif
    @endforeach
</nav>
