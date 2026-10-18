<div class="feature-page">
    <header class="feature-header">
        <a href="{{ route('home') }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">CITY LIFE</span>
            <h1>{{ $title }}</h1>
        </div>
    </header>

    @if ($page === 'notifications')
        @php($unreadCount = count(array_filter($notifications, fn ($notification) => ! $notification['read'])))
        <section class="feature-intro">
            <span class="feature-kicker">YOUR INBOX</span>
            <h2>{{ $unreadCount ? $unreadCount.' new updates' : 'You’re all caught up' }}</h2>
            <p>Church news and reminders, all in one place.</p>
        </section>
        @if ($unreadCount)
            <button type="button" class="feature-text-button" wire:click="markAllRead">Mark all as read</button>
        @endif
        <div class="feature-list notification-list">
            @foreach ($notifications as $notification)
                <article class="feature-row notification-row" wire:key="notification-{{ $notification['id'] }}">
                    <span class="feature-icon">
                        @if ($notification['icon'] === 'calendar')
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3v3m10-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1z"/></svg>
                        @elseif ($notification['icon'] === 'megaphone')
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11v2a1 1 0 0 0 1 1h3l8 4V6L7 10H4a1 1 0 0 0-1 1zM19 9a4 4 0 0 1 0 6"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11a4.8 4.8 0 0 1 8.8-2.6 4.8 4.8 0 0 1 8.8 2.6z"/></svg>
                        @endif
                    </span>
                    <div class="feature-row-copy">
                        <div class="notification-title">
                            <h2>{{ $notification['title'] }}</h2>
                            @unless ($notification['read'])<span class="notification-dot" aria-label="Unread"></span>@endunless
                        </div>
                        <p>{{ $notification['message'] }}</p>
                        @if ($notification['link'] ?? null)<a href="{{ $notification['link'] }}" class="feature-text-button" rel="noopener">Learn more</a>@endif
                        <small>{{ $notification['time'] }}</small>
                    </div>
                    @unless ($notification['read'])
                        <button type="button" class="feature-icon-button" aria-label="Mark {{ $notification['title'] }} as read" wire:click="markRead('{{ $notification['id'] }}')">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
                        </button>
                    @endunless
                </article>
            @endforeach
        </div>
    @elseif ($page === 'app-settings')
        @php($settingLabels = [
            'push_notifications' => ['Push notifications', 'Receive important church updates.'],
            'event_reminders' => ['Event reminders', 'Get a reminder before upcoming events.'],
            'email_updates' => ['Email updates', 'Hear about news and what’s happening.'],
        ])
        <section class="feature-intro">
            <span class="feature-kicker">MAKE IT YOURS</span>
            <h2>Your preferences</h2>
            <p>Choose how City Life keeps you in the loop.</p>
        </section>
        <section class="feature-list settings-list" aria-label="Notification preferences">
            @foreach ($settingLabels as $key => [$label, $description])
                <div class="setting-row" wire:key="setting-{{ $key }}">
                    <div><h2>{{ $label }}</h2><p>{{ $description }}</p></div>
                    <button type="button" role="switch" aria-label="{{ $label }}" aria-checked="{{ $settings[$key] ? 'true' : 'false' }}" @class(['feature-switch', 'is-on' => $settings[$key]]) wire:click="toggleSetting('{{ $key }}')">
                        <span></span>
                    </button>
                </div>
            @endforeach
        </section>
        <p class="feature-footnote">Email updates are saved to your church account. Push and reminder settings are saved on this device and take effect when push notifications launch.</p>
        <a href="{{ route('help-support') }}" wire:navigate class="feature-link-row">
            <span><b>Need help?</b><small>Get answers or contact the church.</small></span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
        </a>
    @endif
</div>
