@php($watch = \App\Support\SermonMedia::watchTarget($sermon['id'], $sermon['video_url'] ?? null))
<a href="{{ $watch['url'] }}" @unless ($watch['native']) wire:navigate @endunless class="card sermon-card" wire:key="{{ $keyPrefix }}-{{ $sermon['id'] }}">
    <div class="sermon-thumb" style="{{ ($sermon['image'] ?? null) ? "background: linear-gradient(to top, rgba(0,0,0,.45), rgba(0,0,0,.05)), url('{$sermon['image']}') center / cover no-repeat, {$sermon['color']}" : "background: linear-gradient(135deg, {$sermon['color']}, #000)" }}">
        <span class="sermon-play"><svg width="18" height="18" viewBox="0 0 24 24" fill="#4f46e5"><path d="M7 4l13 8-13 8z"/></svg></span>
        @if ($sermon['minutes'])<span class="sermon-time">{{ $sermon['minutes'] }} min</span>@endif
    </div>
    <div class="sermon-info">
        <h3>{{ $sermon['title'] }}</h3>
        <p>{{ $sermon['pastor'] }}</p>
        @if ($sermon['date'])<small>{{ \Illuminate\Support\Carbon::parse($sermon['date'])->format('M j, Y') }}</small>@endif
        @if ($sermon['series'])<span class="tag-pill">{{ $sermon['series'] }}</span>@endif
    </div>
</a>
