<div class="feature-page">
    <header class="feature-header">
        <a href="{{ route('bible-reading-plan') }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">DAY {{ $reading['day'] }} OF {{ count($plan['days']) }}</span>
            <h1>{{ $reading['title'] }}</h1>
        </div>
    </header>

    <section class="feature-section">
        <p class="reading-reference">{{ $reading['reference'] }}</p>

        @if ($text && $text['passages'])
            <article class="card passage-text">
                @foreach ($text['passages'] as $passage)
                    <h2 wire:key="passage-{{ $loop->index }}">{{ $passage['reference'] }}</h2>
                    <p>
                        @foreach ($passage['verses'] as $verse)
                            <sup>{{ $verse['verse'] }}</sup>{{ $verse['text'] }}
                        @endforeach
                    </p>
                @endforeach
            </article>

            @if ($text['unavailable'])
                <p class="course-callout" role="status">
                    Part of this reading couldn't be loaded ({{ implode(', ', $text['unavailable']) }}).
                    <button type="button" class="feature-text-button" wire:click="retry">Try again</button>
                </p>
            @endif

            @if ($text['translation']['name'])
                <p class="passage-credit">{{ $text['translation']['name'] }}@if ($text['translation']['note']) ({{ $text['translation']['note'] }})@endif</p>
            @endif
        @else
            <p class="course-callout" role="status">
                @if ($text)
                    The scripture text is busy right now. Read {{ $reading['reference'] }} in your Bible, or try again in a moment.
                @else
                    We can't load the scripture text right now. Read {{ $reading['reference'] }} in your Bible, or try again in a moment.
                @endif
                <button type="button" class="feature-text-button" wire:click="retry">Try again</button>
            </p>
        @endif

        <button type="button" @class(['feature-primary', 'passage-done' => $completed]) wire:click="toggleDone" aria-pressed="{{ $completed ? 'true' : 'false' }}">
            {{ $completed ? 'Completed ✓' : 'Mark as done' }}
        </button>

        <nav class="course-nav" aria-label="Reading navigation">
            @if ($previous)<a href="{{ route('bible-reading-plan.day', $previous) }}" wire:navigate>← Day {{ $previous }}</a>@else<span></span>@endif
            @if ($next)<a href="{{ route('bible-reading-plan.day', $next) }}" wire:navigate>Day {{ $next }} →</a>@endif
        </nav>
    </section>
</div>
