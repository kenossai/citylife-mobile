<div>
    <div class="sermon-sticky">
        <header class="sermon-head">
            <div class="tab-title-row">
                <a href="{{ route('home') }}" wire:navigate data-app-back class="tab-back" aria-label="Back">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 5l-7 7 7 7" />
                    </svg>
                </a>
                <h1>Library</h1>
            </div>
        </header>

        <div class="sermon-tabs" role="tablist">
            @foreach (['sermons' => 'Sermons', 'books' => 'Books', 'playlists' => 'Playlists', 'favourites' => 'Favourites'] as $key => $label)
                <button type="button" role="tab" aria-selected="{{ $tab === $key ? 'true' : 'false' }}"
                    @class(['active' => $tab === $key])
                    wire:click="showTab('{{ $key }}')">{{ $label }}</button>
            @endforeach
        </div>
    </div>

    <div class="sermon-body">
        @if (!$available)
            <p class="sermon-empty">Your library can't be loaded right now. Please check your connection and try again.
            </p>
        @elseif ($tab === 'sermons')
            <button type="button" class="library-shuffle" wire:click="shuffle">
                Shuffle Play
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 3h5v5M4 20 21 3M21 16v5h-5M15 15l6 6M4 4l5 5" />
                </svg>
            </button>

            <h2 class="library-heading">Recently Played</h2>
            @forelse ($recent as $sermon)
                @include('livewire.partials.library-sermon', [
                    'sermon' => $sermon,
                    'keyPrefix' => 'recent',
                ])
            @empty
                <p class="sermon-empty">Sermons you watch will show up here.</p>
            @endforelse
        @elseif ($tab === 'favourites')
            @forelse ($favourites as $sermon)
                @include('livewire.partials.library-sermon', [
                    'sermon' => $sermon,
                    'keyPrefix' => 'favourite',
                ])
            @empty
                <p class="sermon-empty">Tap the heart on a sermon to save it here.</p>
            @endforelse
        @elseif ($tab === 'books')
            @forelse ($books as $book)
                <a href="{{ route('library.book', $book['slug']) }}" wire:navigate class="card sermon-card"
                    wire:key="book-{{ $book['slug'] }}">
                    <div class="sermon-thumb book-thumb"
                        style="{{ $book['cover_url'] ?? null ? "background: url('{$book['cover_url']}') center / cover no-repeat, #292929" : 'background: linear-gradient(135deg, #374151, #000)' }}">
                    </div>
                    <div class="sermon-info">
                        <h3>{{ $book['title'] }}</h3>
                        @if ($book['author'] ?? null)
                            <p>{{ $book['author'] }}</p>
                        @endif
                        @foreach (array_slice($book['categories'] ?? [], 0, 1) as $category)
                            <span class="tag-pill">{{ $category }}</span>
                        @endforeach
                    </div>
                </a>
            @empty
                <p class="sermon-empty">No books yet.</p>
            @endforelse
        @else
            @forelse ($playlists as $series)
                <a href="{{ route('sermons.series', $series['slug']) }}" wire:navigate class="card series-card"
                    wire:key="playlist-{{ $series['slug'] }}">
                    <div class="series-thumb"
                        style="{{ $series['image'] ?? null ? "background: url('{$series['image']}') center / cover no-repeat, {$series['color']}" : "background: linear-gradient(135deg, {$series['color']}, #000)" }}">
                    </div>
                    <div class="series-info">
                        <h3>{{ $series['name'] }}</h3>
                        <p>{{ $series['description'] }}</p>
                    </div>
                </a>
            @empty
                <p class="sermon-empty">No playlists yet.</p>
            @endforelse
        @endif
    </div>
</div>
