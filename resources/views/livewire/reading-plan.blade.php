<div class="feature-page">
    <header class="feature-header">
        <a href="{{ route('home') }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">CITY LIFE</span>
            <h1>Bible Reading Plan</h1>
        </div>
    </header>

    @if ($plan)
        @php($total = count($plan['days']))
        @php($percent = $total ? (int) round(count($completed) / $total * 100) : 0)
        <section class="feature-hero reading-hero">
            <span class="feature-eyebrow">{{ $total }}-DAY PLAN</span>
            <h2>{{ $plan['title'] }}</h2>
            @if ($plan['description'])<p>{{ $plan['description'] }}</p>@endif
            <div class="feature-progress" role="progressbar" aria-label="Reading plan progress" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $percent }}">
                <span style="width: {{ $percent }}%"></span>
            </div>
            <div class="feature-progress-meta">
                <span>{{ count($completed) }} of {{ $total }} days complete</span>
                <span>{{ $percent }}%</span>
            </div>
        </section>
        <section class="feature-section">
            <div class="feature-section-heading">
                <div><span class="feature-kicker">YOUR PLAN</span><h2>Your readings</h2></div>
                <span class="feature-count">{{ $total }} days</span>
            </div>
            <div class="feature-list">
                @foreach ($plan['days'] as $reading)
                    @php($done = in_array($reading['day'], $completed, true))
                    <article class="feature-row reading-row" wire:key="reading-{{ $reading['day'] }}">
                        <span @class(['reading-day', 'is-complete' => $done])>{{ $done ? '✓' : str_pad((string) $reading['day'], 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="feature-row-copy">
                            <small>DAY {{ $reading['day'] }}</small>
                            <h3>{{ $reading['title'] }}</h3>
                            <p>{{ $reading['reference'] }}</p>
                        </div>
                        <button type="button" class="feature-check" aria-pressed="{{ $done ? 'true' : 'false' }}" wire:click="toggle({{ $reading['day'] }})">
                            {{ $done ? 'Completed' : 'Done' }}
                        </button>
                    </article>
                @endforeach
            </div>
            <p class="feature-footnote">Your reading progress is saved on this device.</p>
        </section>
    @else
        <section class="feature-section">
            <p class="feature-footnote">{{ $available ? 'There is no reading plan right now. Check back soon.' : "The reading plan can't be loaded right now. Please check your connection and try again." }}</p>
        </section>
    @endif
</div>
