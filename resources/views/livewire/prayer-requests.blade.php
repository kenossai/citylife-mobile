<div class="prayer-page">
    <header class="prayer-head">
        <div class="prayer-head-row">
            <a href="{{ route('home') }}" wire:navigate class="prayer-back" aria-label="Back">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            </a>
            <h1>Prayer Requests</h1>
        </div>
        <p>Share your prayer needs and pray for others</p>
    </header>

    <div class="prayer-body">
        <div class="prayer-tabs" role="tablist">
            @foreach (['browse' => 'Browse', 'mine' => 'My Requests', 'submit' => 'Submit'] as $key => $label)
                <button type="button" role="tab" aria-selected="{{ $tab === $key ? 'true' : 'false' }}" @class(['active' => $tab === $key]) wire:click="showTab('{{ $key }}')">{{ $label }}</button>
            @endforeach
        </div>

        @if ($tab === 'submit')
            <form class="card prayer-card" wire:submit="submit">
                <h2>Submit a Prayer Request</h2>

                <label for="prayer-name">Your Name (Optional)</label>
                <input id="prayer-name" type="text" wire:model="name" placeholder="John Doe" autocomplete="name">
                <small>Leave blank to submit anonymously</small>
                @error('name')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror

                <label for="prayer-category">Category</label>
                <select id="prayer-category" wire:model="category">
                    @foreach ($categories as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
                @error('category')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror

                <label for="prayer-request">Prayer Request</label>
                <textarea id="prayer-request" wire:model="request" rows="6" placeholder="Share what's on your heart..."></textarea>
                @error('request')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror

                <div class="prayer-notice">
                    <b>Privacy Notice:</b>
                    <p>Your prayer request will be visible to other church members. Please avoid sharing sensitive personal information.</p>
                </div>

                <button type="submit" class="prayer-submit">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>
                    Submit Prayer Request
                </button>
            </form>

            <section class="card prayer-card">
                <h2>Guidelines</h2>
                <ul class="prayer-guidelines">
                    @foreach ($guidelines as $guideline)
                        <li>{{ $guideline }}</li>
                    @endforeach
                </ul>
            </section>
        @else
            @php($visible = $tab === 'mine' ? array_filter($requests, fn ($item) => in_array($item['id'], $mine, true)) : $requests)

            @forelse ($visible as $item)
                <article class="card prayer-card prayer-item" wire:key="prayer-{{ $item['id'] }}">
                    <div class="prayer-item-top">
                        <div class="prayer-avatar">{{ Str::upper(Str::substr($item['name'], 0, 1)) }}</div>
                        <div class="prayer-who">
                            <b>{{ $item['name'] }}</b>
                            <small>{{ $item['date'] }}</small>
                        </div>
                        <span class="prayer-tag">{{ $item['category'] }}</span>
                    </div>
                    <p>{{ $item['text'] }}</p>
                    <div class="prayer-item-foot">
                        <span class="prayer-count">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="#ef5350"><path d="M12 21s-8-5.3-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.7-8 11-8 11z"/></svg>
                            {{ $item['prayers'] }} prayers
                        </span>
                        <button type="button" @class(['prayer-pray', 'done' => $item['prayed']]) wire:click="pray({{ $item['id'] }})" @disabled($item['prayed'])>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="{{ $item['prayed'] ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M12 21s-8-5.3-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.7-8 11-8 11z"/></svg>
                            {{ $item['prayed'] ? 'Prayed' : 'Pray' }}
                        </button>
                    </div>
                </article>
            @empty
                <section class="card prayer-card prayer-empty">
                    <p>You haven't submitted any prayer requests yet.</p>
                </section>
            @endforelse
        @endif
    </div>
</div>
