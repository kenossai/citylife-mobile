<div>
    <header class="detail-hero book-hero">
        <a href="{{ route('library') }}" wire:navigate data-app-back class="back" aria-label="Back">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
        </a>
        @if ($book['cover_url'] ?? null)
            <img src="{{ $book['cover_url'] }}" alt="{{ $book['title'] }}" class="book-cover">
        @endif
    </header>

    <div class="detail-body">
        <h1>{{ $book['title'] }}</h1>
        @if ($book['subtitle'] ?? null)<p class="about">{{ $book['subtitle'] }}</p>@endif
        @if ($book['author'] ?? null)<p class="book-author">by {{ $book['author'] }}</p>@endif

        @if ($book['categories'] ?? [])
            <div class="book-tags">
                @foreach ($book['categories'] as $category)<span class="tag-pill">{{ $category }}</span>@endforeach
            </div>
        @endif

        @if ($book['description'] ?? null)
            <h3>About this book</h3>
            <p class="about">{{ $book['description'] }}</p>
        @endif

        <p class="about book-facts">
            @foreach (array_filter([
                ($book['page_count'] ?? null) ? $book['page_count'].' pages' : null,
                $book['format'] ?? null,
                $book['publisher'] ?? null,
                $book['published_month'] ?? null,
            ]) as $fact){{ $loop->first ? '' : ' · ' }}{{ $fact }}@endforeach
        </p>

        @if ($book['amazon_url'] ?? null)
            <button type="button" class="cta" wire:click="openAmazon">Get it on Amazon</button>
        @endif
        @if ($book['kindle_url'] ?? null)
            <button type="button" class="cta pay-alt" wire:click="openKindle">Read on Kindle</button>
        @endif
    </div>
</div>
