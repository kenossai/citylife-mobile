<div class="player" x-data="player({{ $message['minutes'] * 60 }})" x-init="init()">
    <header class="player-hero" style="background: linear-gradient(160deg, {{ $series['color'] }}, #000)">
        <a href="{{ route('sermons.series', $series['slug']) }}" wire:navigate class="series-back" aria-label="Back">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
        </a>
        <button type="button" class="player-big" @click="toggle()" :aria-label="playing ? 'Pause' : 'Play'">
            <svg x-show="!playing" width="34" height="34" viewBox="0 0 24 24" fill="#4f46e5"><path d="M7 4l13 8-13 8z"/></svg>
            <svg x-show="playing" x-cloak width="34" height="34" viewBox="0 0 24 24" fill="#4f46e5"><path d="M6 4h4v16H6zM14 4h4v16h-4z"/></svg>
        </button>
    </header>

    <div class="player-body">
        <span class="series-pill blue">{{ $series['name'] }} · Message {{ $number }}</span>
        <h1>{{ $message['title'] }}</h1>
        <p class="player-pastor">{{ $message['pastor'] }}</p>

        <input type="range" class="player-bar" min="0" :max="total" step="1" x-model.number="elapsed" :style="`--p:${(elapsed / total) * 100}%`" aria-label="Progress">
        <div class="player-times"><span x-text="format(elapsed)">0:00</span><span x-text="format(total)">0:00</span></div>

        <div class="player-controls">
            @if ($previous)
                <a href="{{ route('sermons.play', [$series['slug'], $previous]) }}" wire:navigate aria-label="Previous message"><svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M6 5h2v14H6zM20 5v14L9 12z"/></svg></a>
            @else
                <span class="off"><svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M6 5h2v14H6zM20 5v14L9 12z"/></svg></span>
            @endif
            <button type="button" @click="skip(-15)" aria-label="Back 15 seconds">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7M3 4v5h5"/></svg><small>15</small>
            </button>
            <button type="button" class="main" @click="toggle()" :aria-label="playing ? 'Pause' : 'Play'">
                <svg x-show="!playing" width="26" height="26" viewBox="0 0 24 24" fill="#fff"><path d="M7 4l13 8-13 8z"/></svg>
                <svg x-show="playing" x-cloak width="26" height="26" viewBox="0 0 24 24" fill="#fff"><path d="M6 4h4v16H6zM14 4h4v16h-4z"/></svg>
            </button>
            <button type="button" @click="skip(15)" aria-label="Forward 15 seconds">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7M21 4v5h-5"/></svg><small>15</small>
            </button>
            @if ($next)
                <a href="{{ route('sermons.play', [$series['slug'], $next]) }}" wire:navigate aria-label="Next message"><svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M16 5h2v14h-2zM4 5v14l11-7z"/></svg></a>
            @else
                <span class="off"><svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M16 5h2v14h-2zM4 5v14l11-7z"/></svg></span>
            @endif
        </div>

        <h2>More in this series</h2>
        @foreach ($series['messages'] as $item)
            <a href="{{ route('sermons.play', [$series['slug'], $loop->iteration]) }}" wire:navigate @class(['card', 'series-message', 'current' => $loop->iteration === $number]) wire:key="up-{{ $loop->iteration }}">
                <span class="series-num">{{ $loop->iteration }}</span>
                <div>
                    <h3>{{ $item['title'] }}</h3>
                    <p><span>{{ $item['minutes'] }} min</span></p>
                </div>
            </a>
        @endforeach
    </div>
</div>

@script
<script>
    Alpine.data('player', (total) => ({
        total, elapsed: 0, playing: false, timer: null,
        init() { this.$watch('elapsed', (v) => { if (v >= this.total) this.stop(); }); },
        toggle() { this.playing ? this.stop() : this.start(); },
        start() {
            if (this.elapsed >= this.total) this.elapsed = 0;
            this.playing = true;
            this.timer = setInterval(() => { this.elapsed = Math.min(this.total, this.elapsed + 1); }, 1000);
        },
        stop() { this.playing = false; clearInterval(this.timer); },
        skip(s) { this.elapsed = Math.max(0, Math.min(this.total, this.elapsed + s)); },
        format(s) { return Math.floor(s / 60) + ':' + String(Math.floor(s % 60)).padStart(2, '0'); },
    }));
</script>
@endscript
