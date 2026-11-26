<div class="feature-page">
    <header class="feature-header">
        <a href="{{ route('home') }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">CITY LIFE</span>
            <h1>Bible</h1>
        </div>
    </header>

    @if ($unavailable)
        <section class="feature-section">
            <p class="feature-footnote">The Bible isn't available right now. Please check your connection and try again later.</p>
            <a href="{{ route('bible-reading-plan') }}" wire:navigate class="feature-link-row">
                <span><b>Reading plan</b><small>Follow a daily reading plan.</small></span>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </section>
    @else
        <section class="feature-section">
            @if ($this->lastTitle)
                <a href="{{ route('bible.read', [$last['book'], $last['chapter']]) }}" wire:navigate class="bible-continue">
                    <small>CONTINUE READING</small>
                    <b>{{ $this->lastTitle }}</b>
                </a>
            @endif

            <div class="bible-tools">
                @if (count($versions) > 1)
                    <select wire:change="selectVersion($event.target.value)" aria-label="Bible version">
                        @foreach ($versions as $version)
                            <option value="{{ $version['id'] }}" @selected($version['id'] === $versionId)>{{ $version['abbreviation'] }} · {{ $version['title'] }}</option>
                        @endforeach
                    </select>
                @else
                    <span class="bible-version-name">{{ $versions[0]['abbreviation'] }} · {{ $versions[0]['title'] }}</span>
                @endif
                <input type="search" wire:model.live.debounce.250ms="search" placeholder="Find a book…" aria-label="Find a book" autocapitalize="off">
            </div>

            @if ($search === '')
                <div class="prayer-tabs course-tabs" role="tablist">
                    <button type="button" role="tab" aria-selected="{{ $testament === 'old_testament' ? 'true' : 'false' }}" @class(['active' => $testament === 'old_testament']) wire:click="$set('testament', 'old_testament')">Old Testament</button>
                    <button type="button" role="tab" aria-selected="{{ $testament === 'new_testament' ? 'true' : 'false' }}" @class(['active' => $testament === 'new_testament']) wire:click="$set('testament', 'new_testament')">New Testament</button>
                </div>
            @endif

            <div class="feature-list">
                @forelse ($this->visibleBooks as $book)
                    <a href="{{ route('bible.book', $book['id']) }}" wire:navigate class="feature-row course-row" wire:key="book-{{ $book['id'] }}">
                        <div class="feature-row-copy">
                            <h3>{{ $book['title'] }}</h3>
                            <small>{{ $book['chapters'] }} {{ \Illuminate\Support\Str::plural('chapter', $book['chapters']) }}</small>
                        </div>
                        <svg class="course-chev" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                    </a>
                @empty
                    <p class="feature-footnote">No books found.</p>
                @endforelse
            </div>

            <a href="{{ route('bible-reading-plan') }}" wire:navigate class="feature-link-row">
                <span><b>Reading plan</b><small>Follow a daily reading plan.</small></span>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        </section>
    @endif
</div>
