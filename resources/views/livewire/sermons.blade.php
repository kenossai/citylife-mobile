<div>
    <div class="sermon-sticky">
    <header class="sermon-head">
        <h1>Sermons</h1>
        <label class="sermon-search">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
            <input type="search" wire:model.live.debounce.300ms="search" placeholder="Search sermons..." aria-label="Search sermons">
        </label>
    </header>

        <div class="sermon-tabs" role="tablist">
            @foreach (['recent' => 'Recent', 'series' => 'Series', 'popular' => 'Popular'] as $key => $label)
                <button type="button" role="tab" aria-selected="{{ $tab === $key ? 'true' : 'false' }}" @class(['active' => $tab === $key]) wire:click="showTab('{{ $key }}')">{{ $label }}</button>
            @endforeach
        </div>
    </div>

    <div class="sermon-body">
        @if ($tab === 'series')
            @forelse ($this->seriesList as $series)
                <a href="{{ route('sermons.series', $series['slug']) }}" wire:navigate class="card series-card" wire:key="series-{{ $series['slug'] }}">
                    <div class="series-thumb" style="background: linear-gradient(135deg, {{ $series['color'] }}, #000)"></div>
                    <div class="series-info">
                        <h3>{{ $series['name'] }}</h3>
                        <p>{{ $series['description'] }}</p>
                        <small>
                            <span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="14" height="14" rx="2"/><path d="M21 8v10M10 9l4 3-4 3z"/></svg>
                                {{ count($series['messages']) }} {{ Str::plural('sermon', count($series['messages'])) }}
                            </span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </small>
                    </div>
                </a>
            @empty
                <p class="sermon-empty">No series found.</p>
            @endforelse
        @else
        @forelse ($this->results as $sermon)
            <article class="card sermon-card" wire:key="sermon-{{ $tab }}-{{ $sermon['id'] }}">
                <div class="sermon-thumb" style="background: linear-gradient(135deg, {{ $sermon['color'] }}, #000)">
                    <span class="sermon-play"><svg width="18" height="18" viewBox="0 0 24 24" fill="#4f46e5"><path d="M7 4l13 8-13 8z"/></svg></span>
                    <span class="sermon-time">{{ $sermon['minutes'] }} min</span>
                </div>
                <div class="sermon-info">
                    <h3>{{ $sermon['title'] }}</h3>
                    <p>{{ $sermon['pastor'] }}</p>
                    <small>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M7 2v3M17 2v3M4 8h16M5 4h14a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z"/></svg>
                        {{ \Illuminate\Support\Carbon::parse($sermon['date'])->format('M j, Y') }}
                    </small>
                    <span class="tag-pill">{{ $sermon['series'] }}</span>
                </div>
            </article>
        @empty
            <p class="sermon-empty">No sermons found.</p>
        @endforelse
        @endif
    </div>
</div>
