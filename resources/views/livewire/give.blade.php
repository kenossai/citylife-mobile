<div>
    <header class="give-head">
        <h1>Give</h1>
        <p>Your generosity helps us serve our community</p>
    </header>

    <div class="give-body">
        @if ($given)
            <div class="card give-thanks">
                <span class="give-check"><svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12l5 5 9-10"/></svg></span>
                <h2>Thank you!</h2>
                <p>Your {{ strtolower(\App\Livewire\Give::FREQUENCIES[$frequency]) }} gift of <b>£{{ number_format($this->amount, 2) }}</b> to {{ $fund }} has been recorded.</p>
                <button type="button" class="give-btn" wire:click="another">Give again</button>
            </div>
        @else
            <form wire:submit="give">
                <div class="give-freq" role="tablist">
                    @foreach (\App\Livewire\Give::FREQUENCIES as $key => $label)
                        <button type="button" role="tab" aria-selected="{{ $frequency === $key ? 'true' : 'false' }}" @class(['active' => $frequency === $key]) wire:click="$set('frequency', '{{ $key }}')">{{ $label }}</button>
                    @endforeach
                </div>

                <h2>Amount</h2>
                <div class="give-amounts">
                    @foreach (\App\Livewire\Give::AMOUNTS as $amount)
                        <button type="button" @class(['active' => $preset === $amount]) wire:click="choose({{ $amount }})">£{{ $amount }}</button>
                    @endforeach
                </div>
                <label class="give-custom">
                    <span>£</span>
                    <input type="number" inputmode="decimal" min="1" step="0.01" wire:model.live.debounce.300ms="custom" placeholder="Other amount" aria-label="Other amount">
                </label>
                @error('custom') <p class="give-error">{{ $message }}</p> @enderror

                <h2>Give to</h2>
                <div class="card give-funds">
                    @foreach (\App\Livewire\Give::FUNDS as $name)
                        <label wire:key="fund-{{ $loop->index }}">
                            <input type="radio" wire:model.live="fund" value="{{ $name }}">
                            <span>{{ $name }}</span>
                            <i aria-hidden="true"></i>
                        </label>
                    @endforeach
                </div>
                @error('fund') <p class="give-error">{{ $message }}</p> @enderror

                <button type="submit" class="give-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-8-5.3-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 5.7-8 11-8 11z"/></svg>
                    Give £{{ number_format($this->amount, 2) }}
                </button>
                <p class="give-note">Secure giving. Payments are not processed in this preview.</p>
            </form>
        @endif
    </div>
</div>
