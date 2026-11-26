<div class="feature-page bible-reader">
    <link rel="stylesheet" href="https://cdn.youversion.com/platform/1/bible.css">
    <link rel="stylesheet" href="{{ $fontsUrl }}">

    <header class="feature-header">
        <a href="{{ route('bible.book', $info['id']) }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">{{ collect($versions)->firstWhere('id', $versionId)['abbreviation'] ?? 'BIBLE' }}</span>
            <h1>{{ $info['title'] }} {{ $chapterNumber }}</h1>
        </div>
    </header>

    <section class="feature-section">
        <div class="bible-tools">
            @if (count($versions) > 1)
                <select wire:change="selectVersion($event.target.value)" aria-label="Bible version">
                    @foreach ($versions as $version)
                        <option value="{{ $version['id'] }}" @selected($version['id'] === $versionId)>{{ $version['abbreviation'] }} · {{ $version['title'] }}</option>
                    @endforeach
                </select>
            @endif
            <div class="bible-size" role="group" aria-label="Text size">
                <button type="button" wire:click="resize(-2)" aria-label="Smaller text">A−</button>
                <button type="button" wire:click="resize(2)" aria-label="Larger text">A+</button>
            </div>
        </div>

        @if ($passage)
            <article class="card bible-page">
                <div data-yv-sdk data-yv-theme="{{ \App\Support\Theme::resolved() }}" data-slot="yv-bible-renderer" style="--yv-reader-font-size: {{ $fontSize }}px">{!! $passage['html'] !!}</div>
            </article>

            <p class="passage-credit">{{ $passage['copyright'] }}</p>

            <nav class="course-nav" aria-label="Chapter navigation">
                @if ($passage['previous'])
                    <a href="{{ route('bible.read', [$passage['previous']['book'], $passage['previous']['chapter']]) }}" wire:navigate>← Previous</a>
                @else
                    <span></span>
                @endif
                @if ($passage['next'])
                    <a href="{{ route('bible.read', [$passage['next']['book'], $passage['next']['chapter']]) }}" wire:navigate>Next →</a>
                @endif
            </nav>
        @else
            <p class="course-callout" role="status">
                We can't load this chapter right now.
                <button type="button" class="feature-text-button" wire:click="retry">Try again</button>
            </p>
        @endif
    </section>
</div>
