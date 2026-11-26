<div>
    <div class="tab-head">
        <a href="{{ route('home') }}" wire:navigate data-app-back class="tab-back" aria-label="Back">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
        </a>
        <h1 class="page-title">Events</h1>
    </div>

    <div class="chips">
        @foreach ($categories as $name)
            <button type="button" wire:click="$set('category', '{{ $name }}')" @class(['chip', 'active' => $category === $name])>{{ $name }}</button>
        @endforeach
    </div>

    <div class="content" style="margin-top:0">
        @forelse ($this->filtered as $event)
            <a href="{{ route('events.show', $event['id']) }}" wire:navigate wire:key="event-{{ $event['id'] }}" class="card event" style="display:flex">
                <div class="date"><small>{{ $event['month'] }}</small><b>{{ $event['day'] }}</b></div>
                <div style="flex:1">
                    <h4>{{ $event['title'] }}</h4>
                    <p>{{ $event['time'] }} &nbsp;•&nbsp; {{ $event['place'] }}</p>
                </div>
                <span class="tag-pill">{{ $event['category'] }}</span>
            </a>
        @empty
            <p style="text-align:center;color:#6b7280;margin-top:40px">No events in this category.</p>
        @endforelse
    </div>
</div>
