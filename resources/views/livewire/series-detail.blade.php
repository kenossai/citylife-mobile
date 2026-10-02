<div>
    <header class="series-hero" style="background: linear-gradient(160deg, {{ $series['color'] }}, #000)">
        <a href="{{ route('sermons') }}" wire:navigate class="series-back" aria-label="Back">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
        </a>
        <div>
            <h1>{{ $series['name'] }}</h1>
            <p>{{ count($series['messages']) }} {{ Str::plural('sermon', count($series['messages'])) }}</p>
        </div>
    </header>

    <div class="series-body">
        <h2>About This Series</h2>
        <div class="card series-about">
            <p>{{ $series['description'] }}</p>
            <div>
                <span class="series-pill blue">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="14" height="14" rx="2"/><path d="M21 8v10M10 9l4 3-4 3z"/></svg>
                    {{ count($series['messages']) }} Messages
                </span>
                <span class="series-pill purple">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                    ~{{ $average }} min each
                </span>
            </div>
        </div>

        <h2>Messages</h2>
        @foreach ($series['messages'] as $message)
            <a href="{{ route('sermons.play', [$series['slug'], $loop->iteration]) }}" wire:navigate class="card series-message" wire:key="message-{{ $loop->iteration }}">
                <span class="series-num">{{ $loop->iteration }}</span>
                <div>
                    <h3>{{ $message['title'] }}</h3>
                    <p>
                        <span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                            {{ $message['pastor'] }}
                        </span>
                        <span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                            {{ $message['minutes'] }} min
                        </span>
                    </p>
                </div>
                <span class="series-play" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="#4f46e5"><path d="M7 4l13 8-13 8z"/></svg></span>
            </a>
        @endforeach
    </div>
</div>
