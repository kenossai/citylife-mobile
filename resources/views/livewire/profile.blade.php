@php
    $icons = [
        'Email' => ['#e8f5e9', '#43a047', 'M3 6h18v12H3zM3 6l9 7 9-7'],
        'Phone Number' => ['#fff1dc', '#fb8c00', 'M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z'],
        'Address' => ['#e0f5f8', '#00acc1', 'M12 21s-7-6.5-7-12a7 7 0 0 1 14 0c0 5.5-7 12-7 12zM12 11.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5z'],
        'Member ID' => ['#e3ebff', '#4f7df3', 'M4 4h16v11H4zM9 15v5l3-2 3 2v-5'],
        'Joined Date' => ['#ede7f6', '#5e35b1', 'M7 2v3M17 2v3M4 8h16M5 4h14a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z'],
        'Ministry Group' => ['#fce4ec', '#e91e63', 'M9 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM2 20c0-3 3-5 7-5s7 2 7 5zM17 5a3 3 0 0 1 0 6M20 15c1.5.7 2 2 2 5'],
    ];
    $preferenceItems = [
        'pushNotifications' => ['Push Notifications', '#fff1dc', '#fb8c00', 'M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9M10 21a2 2 0 0 0 4 0'],
        'emailNewsletters' => ['Email Newsletters', '#e8f5e9', '#43a047', 'M3 6h18v12H3zM3 6l9 7 9-7'],
        'eventReminders' => ['Event Reminders', '#e3f0fd', '#1e88e5', 'M7 2v3M17 2v3M4 8h16M5 4h14a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1z'],
    ];
@endphp

<div class="profile-page">

    <header class="p-head">
        <a href="{{ route('home') }}" wire:navigate class="p-back" aria-label="Back">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 5l-7 7 7 7"/></svg>
        </a>
        <span class="p-title">Profile</span>
        <div class="p-avatar">{{ collect(explode(' ', $name))->map(fn ($part) => $part[0])->join('') }}</div>
        <h1>{{ $name }}</h1>
        <p>Member since {{ $memberSince }}</p>
    </header>

    <div class="p-body">
        <h2>Contact Information</h2>
        <section class="card p-card">
            @foreach ($contact as $label => $value)
                @php([$background, $color, $path] = $icons[$label])
                <div class="p-row" wire:key="contact-{{ $label }}">
                    <div class="p-ico" style="background: {{ $background }}; color: {{ $color }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $path }}"/></svg></div>
                    <div><small>{{ $label }}</small><b>{{ $value }}</b></div>
                </div>
            @endforeach
        </section>

        <h2>Membership Details</h2>
        <section class="card p-card">
            @foreach ($membership as $label => $value)
                @php([$background, $color, $path] = $icons[$label])
                <div class="p-row" wire:key="membership-{{ $label }}">
                    <div class="p-ico" style="background: {{ $background }}; color: {{ $color }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $path }}"/></svg></div>
                    <div><small>{{ $label }}</small><b>{{ $value }}</b></div>
                </div>
            @endforeach
        </section>

        <h2>Preferences</h2>
        <section class="card p-card">
            @foreach ($preferenceItems as $key => [$label, $background, $color, $path])
                <div class="p-row" wire:key="preference-{{ $key }}">
                    <div class="p-ico" style="background: {{ $background }}; color: {{ $color }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $path }}"/></svg></div>
                    <span class="p-label">{{ $label }}</span>
                    <button type="button" role="switch" aria-checked="{{ $preferences[$key] ? 'true' : 'false' }}" aria-label="{{ $label }}" @class(['p-switch', 'on' => $preferences[$key]]) wire:click="togglePreference('{{ $key }}')"></button>
                </div>
            @endforeach
        </section>

        <button type="button" class="p-btn primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 11h14v10H5zM8 11V8a4 4 0 0 1 8 0v3"/></svg>
            Change Password
        </button>
        <button type="button" class="p-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6zM12 11v5M12 8v.01"/></svg>
            Privacy Settings
        </button>
        <button type="button" class="p-btn danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3"/></svg>
            Delete Account
        </button>
    </div>
</div>
