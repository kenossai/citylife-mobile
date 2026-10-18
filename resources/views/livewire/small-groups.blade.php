<div class="feature-page">
    <header class="feature-header">
        <a href="{{ route('home') }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">CITY LIFE</span>
            <h1>Small Groups</h1>
        </div>
    </header>

    <section class="feature-hero group-hero">
        <span class="feature-eyebrow">LIFE IS BETTER TOGETHER</span>
        <h2>Find your people</h2>
        <p>Connect, grow in faith and make room for real community.</p>
    </section>

    <section class="feature-section">
        <div class="feature-section-heading">
            <div><span class="feature-kicker">GET CONNECTED</span><h2>Explore a group</h2></div>
        </div>

        @if (! $available)
            <p class="feature-footnote">Groups can't be loaded right now. Please check your connection and try again.</p>
        @endif

        <div class="feature-list">
            @forelse ($groups as $group)
                <article class="feature-row group-row group-card" wire:key="group-{{ $group['slug'] }}">
                    <div class="feature-row-copy">
                        @if ($group['category'])<span class="course-pill">{{ $group['category'] }}</span>@endif
                        <h3>{{ $group['name'] }}</h3>
                        @if ($group['subtitle'])<p>{{ $group['subtitle'] }}</p>@endif
                        @if ($group['meeting_schedule'])<small>{{ $group['meeting_schedule'] }}@if ($group['location']) · {{ $group['location'] }}@endif</small>@endif

                        @if (in_array($group['slug'], $sent, true))
                            <p class="course-callout" role="status">Thanks! We'll be in touch soon.</p>
                        @elseif ($openGroup === $group['slug'])
                            <form class="group-form" wire:submit="connect('{{ $group['slug'] }}')">
                                <input type="text" wire:model="name" placeholder="Your name" autocomplete="name">
                                @error('name')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror
                                <input type="email" wire:model="email" placeholder="Email" autocomplete="email" inputmode="email" autocapitalize="off">
                                @error('email')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror
                                <input type="tel" wire:model="phone" placeholder="Phone (optional)" autocomplete="tel">
                                <textarea wire:model="message" rows="3" placeholder="Anything you'd like us to know? (optional)"></textarea>
                                @if ($error)<p class="prayer-error" role="alert">{{ $error }}</p>@endif
                                <button type="submit" class="prayer-submit" wire:loading.attr="disabled">Send</button>
                            </form>
                        @else
                            <button type="button" class="feature-check" wire:click="open('{{ $group['slug'] }}')">I'm interested</button>
                        @endif
                    </div>
                </article>
            @empty
                @if ($available)<p class="feature-footnote">No groups are listed right now.</p>@endif
            @endforelse
        </div>
    </section>
</div>
