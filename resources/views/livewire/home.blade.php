<div>
    <header class="hero">
        <div class="hero-row">
            <div class="avatar">CITY LIFE</div>
            <div>
                <small>Welcome back!</small>
                <strong>{{ $name }}</strong>
            </div>
            <a href="{{ route('notifications') }}" wire:navigate class="bell" aria-label="Notifications">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9M10 21a2 2 0 0 0 4 0"/></svg>
                @if ($notifications)<i>{{ $notifications }}</i>@endif
            </a>
        </div>
    </header>

    <div class="content">
        <div class="card quick">
            <a href="{{ route('events') }}" wire:navigate><span class="ico" style="background:#254b3b"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M7 2v3M17 2v3M4 8h16M5 4h14a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z"/></svg></span>Events</a>
            <a href="{{ route('sermons') }}" wire:navigate><span class="ico" style="background:#557a5c"><svg width="30" height="30" viewBox="0 0 24 24" fill="#fff"><path d="M3 7a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v3l5-3v10l-5-3v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></span>Sermons</a>
            <a href="{{ route('give') }}" wire:navigate><span class="ico" style="background:#c9634d"><svg width="30" height="30" viewBox="0 0 24 24" fill="#fff"><path d="M12 21s-8-5.3-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.7-8 11-8 11z"/></svg></span>Give</a>
            <a href="{{ route('course') }}" wire:navigate><span class="ico" style="background:#b8832b"><svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linejoin="round"><path d="M12 6c-2-1.5-5-2-9-2v14c4 0 7 .5 9 2 2-1.5 5-2 9-2V4c-4 0-7 .5-9 2zM12 6v14"/></svg></span>Course</a>
        </div>

        @if ($isLive)
            <section class="live">
                <div>
                    <div class="tag">LIVE NOW</div>
                    <h2>Sunday Service</h2>
                    <p>Join us in worship</p>
                </div>
                <a href="{{ route('sermons') }}" wire:navigate class="watch">Watch</a>
            </section>
        @endif

        <div class="section-head">
            <h3>Upcoming Events</h3>
            <a href="{{ route('events') }}" wire:navigate>View All</a>
        </div>

        @foreach ($events as $event)
            <a href="{{ route('events.show', $event['id']) }}" wire:navigate class="card event">
                <div class="date"><small>{{ $event['month'] }}</small><b>{{ $event['day'] }}</b></div>
                <div>
                    <h4>{{ $event['title'] }}</h4>
                    <p>{{ $event['time'] }} &nbsp;•&nbsp; {{ $event['place'] }}</p>
                </div>
            </a>
        @endforeach
    </div>
</div>
