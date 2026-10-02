<div>
    <header class="detail-hero">
        <a href="{{ route('events') }}" wire:navigate class="back" aria-label="Back">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
        </a>
        <span class="tag-pill" style="background:rgba(255,255,255,.9)">{{ $event['category'] }}</span>
    </header>

    <div class="detail-body">
        <h1>{{ $event['title'] }}</h1>

        <div class="card info">
            <div class="row">
                <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M7 2v3M17 2v3M4 8h16M5 4h14a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z"/></svg></div>
                <div><b>{{ $event['date'] }}</b><p>{{ $event['time'] }} – {{ $event['end'] }}</p></div>
            </div>
            <div class="row">
                <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.5-7-12a7 7 0 0 1 14 0c0 5.5-7 12-7 12zM12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z"/></svg></div>
                <div><b>{{ $event['place'] }}</b><p>{{ $event['address'] }}</p></div>
            </div>
        </div>

        <h3>About this event</h3>
        <p class="about">{{ $event['about'] }}</p>

        <button type="button" wire:click="toggleRegistration" @class(['cta', 'done' => $registered])>
            {{ $registered ? "You're registered ✓" : 'Register' }}
        </button>
    </div>
</div>
