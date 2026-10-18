<div class="player">
    <header class="player-hero" data-media style="{{ ($sermon['thumbnail_url'] ?? null) ? "background: linear-gradient(to bottom, rgba(0,0,0,.3), rgba(0,0,0,.6)), url('{$sermon['thumbnail_url']}') center / cover no-repeat, #292929" : ('background: linear-gradient(160deg, '.($sermon['series']['color'] ?? '#292929').', #000)') }}">
        <a href="{{ route('sermons') }}" wire:navigate data-app-back class="series-back" aria-label="Back">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
        </a>
    </header>

    <div class="player-body">
        @if ($sermon['series'] ?? null)
            <a href="{{ route('sermons.series', $sermon['series']['slug']) }}" wire:navigate class="series-pill blue">{{ $sermon['series']['title'] }}</a>
        @endif
        <h1>{{ $sermon['title'] }}</h1>
        <p class="player-pastor">
            {{ $sermon['speaker'] ?: 'City Life Church' }}
            @if ($sermon['preached_at'] ?? null) · {{ \Illuminate\Support\Carbon::parse($sermon['preached_at'])->format('M j, Y') }}@endif
        </p>

        @if ($media)
            <div class="player-media">
                @if ($media['type'] === 'youtube')
                    <iframe src="{{ $media['src'] }}" title="{{ $sermon['title'] }}" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen loading="lazy"></iframe>
                @elseif ($media['type'] === 'video')
                    <video src="{{ $media['src'] }}" controls playsinline preload="metadata"></video>
                @elseif ($media['type'] === 'audio')
                    <audio src="{{ $media['src'] }}" controls preload="metadata"></audio>
                @else
                    <a href="{{ $media['src'] }}" class="player-link" rel="noopener">Watch this message</a>
                @endif
            </div>
        @else
            <p class="feature-footnote">The recording for this message isn't available yet.</p>
        @endif

        @if ($sermon['scripture'] ?? [])
            <p class="sermon-scripture">{{ implode(' · ', $sermon['scripture']) }}</p>
        @endif

        @if ($sermon['description'] ?? null)
            <p class="sermon-description">{{ strip_tags($sermon['description']) }}</p>
        @endif

        <small class="sermon-views">{{ number_format($sermon['views'] ?? 0) }} {{ \Illuminate\Support\Str::plural('view', $sermon['views'] ?? 0) }}</small>
    </div>
</div>
