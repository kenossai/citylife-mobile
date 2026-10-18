<div>
    <header class="give-head">
        <h1>Give</h1>
        <p>Your generosity helps us serve our community</p>
    </header>

    <div class="give-body">
        @php($bank = array_filter($giving['bank'] ?? []))
        @php($hasOnline = filled($giving['card_url'] ?? null) || filled($giving['paypal_url'] ?? null))

        @if ($hasOnline)
            <h2>Give online</h2>
            <div class="give-methods">
                @if ($giving['card_url'] ?? null)
                    <button type="button" class="give-method primary" wire:click="openCard">
                        <b>Give by card</b>
                        <span>Secure card payment. Opens in a browser window.</span>
                    </button>
                @endif
                @if ($giving['paypal_url'] ?? null)
                    <button type="button" class="give-method" wire:click="openPayPal">
                        <b>Give with PayPal</b>
                        <span>Use your PayPal account or any debit or credit card.</span>
                    </button>
                @endif
            </div>
        @endif

        @if ($bank)
            <h2>Bank transfer</h2>
            <div class="card give-bank">
                @foreach (['bank' => 'Bank', 'account_name' => 'Account name', 'sort_code' => 'Sort code', 'account_number' => 'Account number', 'reference' => 'Reference'] as $key => $label)
                    @if ($bank[$key] ?? null)
                        <div><span>{{ $label }}</span><b>{{ $bank[$key] }}</b></div>
                    @endif
                @endforeach
            </div>
        @endif

        @if ($giving['in_person'] ?? null)
            <h2>In person</h2>
            <p class="give-note">{{ $giving['in_person'] }}</p>
        @endif

        @unless ($hasOnline || $bank || ($giving['in_person'] ?? null))
            <p class="give-note">Giving details can't be loaded right now. Please check your connection and try again.</p>
        @endunless

        <h2>Gift Aid</h2>
        @if ($giftAidSent)
            <p class="course-callout" role="status">Thank you! Your Gift Aid declaration has been received.</p>
        @else
            <div class="card give-giftaid">
                <p>If you're a UK taxpayer, we can claim Gift Aid on your gifts, boosting them by 25% at no extra cost to you.</p>

                @if ($giftAidOpen)
                    <form wire:submit="submitGiftAid">
                        <input type="text" wire:model="firstName" placeholder="First name" autocomplete="given-name">
                        @error('firstName')<p class="give-error">{{ $message }}</p>@enderror
                        <input type="text" wire:model="lastName" placeholder="Last name" autocomplete="family-name">
                        @error('lastName')<p class="give-error">{{ $message }}</p>@enderror
                        <input type="text" wire:model="address" placeholder="Address" autocomplete="street-address">
                        @error('address')<p class="give-error">{{ $message }}</p>@enderror
                        <input type="text" wire:model="postcode" placeholder="Postcode" autocomplete="postal-code" autocapitalize="characters">
                        @error('postcode')<p class="give-error">{{ $message }}</p>@enderror
                        <input type="tel" wire:model="phone" placeholder="Phone number" autocomplete="tel">
                        @error('phone')<p class="give-error">{{ $message }}</p>@enderror
                        <input type="email" wire:model="email" placeholder="Email address" autocomplete="email" inputmode="email" autocapitalize="off">
                        @error('email')<p class="give-error">{{ $message }}</p>@enderror
                        <input type="text" wire:model="giftAidCode" placeholder="Your Gift Aid code" autocapitalize="off">
                        <small>For example: JS-tithe, AB-offering, CD-missions</small>
                        @error('giftAidCode')<p class="give-error">{{ $message }}</p>@enderror
                        <label class="give-date"><span>Declaration date</span><input type="date" wire:model="declarationDate"></label>
                        @error('declarationDate')<p class="give-error">{{ $message }}</p>@enderror
                        <label class="prayer-check">
                            <input type="checkbox" wire:model="confirmed">
                            <span>I confirm I have paid or will pay Income Tax and/or Capital Gains Tax at least equal to the tax City Life International will reclaim on my gifts.</span>
                        </label>
                        @error('confirmed')<p class="give-error">{{ $message }}</p>@enderror
                        @if ($error)<p class="give-error" role="alert">{{ $error }}</p>@endif
                        <button type="submit" class="give-btn" wire:loading.attr="disabled">Submit declaration</button>
                    </form>
                @else
                    <button type="button" class="give-btn" wire:click="toggleGiftAid">Make a Gift Aid declaration</button>
                @endif
            </div>
        @endif
    </div>
</div>
