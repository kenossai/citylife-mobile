<div>
    <header class="hero">
        <div class="hero-row">
            <div class="avatar">CITY LIFE</div>
            <div>
                <small>{{ $this->greeting() }} 👋</small>
                <strong>{{ $name }}</strong>
            </div>
            <a href="{{ route('notifications') }}" wire:navigate class="bell" aria-label="Notifications">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9M10 21a2 2 0 0 0 4 0"/></svg>
                @if ($notifications)<i>{{ $notifications }}</i>@endif
            </a>
        </div>
    </header>

    <div class="content home-content">
        @if ($isLive)
            <section class="live">
                <div>
                    <div class="tag">LIVE NOW</div>
                    <h2>{{ $liveTitle }}</h2>
                    <p>{{ $liveSubtitle }}</p>
                </div>
                <a href="{{ route('sermons') }}" wire:navigate class="watch">Watch</a>
            </section>
        @endif

        <section class="home-verse">
            <span>Verse of the Day</span>
            <blockquote>“{{ $verse['text'] }}”</blockquote>
            <cite>{{ $verse['reference'] }}</cite>
        </section>

        <div class="home-shortcuts">
            @foreach ($shortcuts as $shortcut)
                <a href="{{ route($shortcut['route']) }}" wire:navigate>
                    <i><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $shortcut['icon'] }}"/></svg></i>
                    <span>{{ $shortcut['label'] }}</span>
                </a>
            @endforeach
        </div>

        <div class="section-head">
            <h3>Latest Series</h3>
            <a href="{{ route('sermons') }}" wire:navigate>View All</a>
        </div>

        <div class="home-series">
            @foreach ($series as $item)
                <a href="{{ route('sermons.series', $item['slug']) }}" wire:navigate style="--series: {{ ($item['image'] ?? null) ? "url('{$item['image']}') center / cover no-repeat, {$item['color']}" : $item['color'] }}">
                    <small>{{ count($item['messages']) }} messages</small>
                    <strong>{{ $item['name'] }}</strong>
                    <p>{{ $item['description'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="section-head">
            <h3>Upcoming Events</h3>
            <a href="{{ route('events') }}" wire:navigate>View All</a>
        </div>

        @foreach ($events as $event)
            <a href="{{ route('events.show', $event['id']) }}" wire:navigate class="card event">
                <div class="date"><small>{{ $event['month'] }}</small><b>{{ $event['day'] }}</b></div>
                <div>
                    <h4>{{ $event['title'] }}</h4>
                    <p>{{ $event['time'] }} &nbsp;•&nbsp; {{ $event['place'] }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
