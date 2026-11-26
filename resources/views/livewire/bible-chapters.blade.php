<div class="feature-page">
    <header class="feature-header">
        <a href="{{ route('bible') }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">{{ $versions[0]['abbreviation'] ?? 'BIBLE' }}</span>
            <h1>{{ $info['title'] }}</h1>
        </div>
    </header>

    <section class="feature-section">
        <p class="feature-footnote">Choose a chapter</p>
        <div class="bible-chapters">
            @foreach (range(1, $info['chapters']) as $chapter)
                <a href="{{ route('bible.read', [$info['id'], $chapter]) }}" wire:navigate wire:key="chapter-{{ $chapter }}">{{ $chapter }}</a>
            @endforeach
        </div>
    </section>
</div>
