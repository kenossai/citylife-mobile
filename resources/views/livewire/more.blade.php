<div>
    <a href="{{ route('profile') }}" wire:navigate class="profile-head">
        <div class="badge">{{ collect(explode(' ', $name))->map(fn ($n) => $n[0])->join('') }}</div>
        <div>
            <h1>{{ $name }}</h1>
            <p>Member since {{ $memberSince }}</p>
        </div>
        <svg class="chev" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
    </a>

    @foreach ($sections as $title => $items)
        <h2 class="menu-title">{{ $title }}</h2>
        <nav class="card menu">
            @foreach ($items as [$label, $icon, $count, $route])
                <a href="{{ route($route) }}" wire:navigate @click="more = false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icon }}"/></svg>
                    <span>{{ $label }}</span>
                    @if ($count)<span class="count">{{ $count }}</span>@endif
                    <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                </a>
            @endforeach
        </nav>
    @endforeach

    <button type="button" class="signout">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
        Sign Out
    </button>

    <footer class="app-version">
        <p>CityLife Church App</p>
        <p>Version {{ $version }}</p>
    </footer>
</div>
