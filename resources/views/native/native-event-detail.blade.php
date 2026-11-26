<native:top-bar :title="$event['title'] ?? 'Event'" back />

@if ($event === [])
    <native:column class="w-full h-full items-center justify-center gap-2 p-8 bg-theme-background">
        <native:text class="text-lg font-bold text-theme-on-surface">We can't find that event</native:text>
        <native:button label="Back to events" @press="back" />
    </native:column>
@else
    <native:scroll-view class="w-full h-full bg-theme-background">
        <native:column class="w-full gap-4 p-4">
            @if ($event['image'] ?? null)
                <native:image :src="$event['image']" :alt="$event['title']" class="w-full h-[200dp] rounded-lg" />
            @endif

            <native:text class="text-2xl font-bold text-theme-on-surface">{{ $event['title'] }}</native:text>

            <native:column class="w-full gap-3 p-4 rounded-lg bg-theme-surface">
                <native:row class="w-full items-center gap-3">
                    <native:icon ios="calendar" android="calendar_today" :size="22" class="text-theme-primary" />
                    <native:column class="flex-1">
                        <native:text class="font-bold text-theme-on-surface">{{ $event['date'] }}</native:text>
                        <native:text class="text-theme-on-surface-variant">{{ $event['time'] }}@if ($event['end']) – {{ $event['end'] }}@endif</native:text>
                    </native:column>
                </native:row>

                <native:row class="w-full items-center gap-3">
                    <native:icon ios="mappin.and.ellipse" android="location_on" :size="22" class="text-theme-primary" />
                    <native:column class="flex-1">
                        <native:text class="font-bold text-theme-on-surface">{{ $event['place'] }}</native:text>
                        @if ($event['address'])
                            <native:text class="text-theme-on-surface-variant">{{ $event['address'] }}</native:text>
                        @endif
                    </native:column>
                </native:row>
            </native:column>

            @if ($event['about'])
                <native:text class="text-lg font-bold text-theme-on-surface">About this event</native:text>
                <native:text class="text-theme-on-surface-variant">{{ $event['about'] }}</native:text>
            @endif

            @if ($event['requires_payment'] ?? false)
                <native:text class="text-lg font-bold text-theme-on-surface">Pay for this event</native:text>
                @if ($giving['card_url'] ?? null)
                    <native:button label="Pay by card" class="w-full" @press="payByCard" />
                @endif
                @if ($giving['paypal_url'] ?? null)
                    <native:button label="Pay with PayPal" variant="secondary" class="w-full" @press="payWithPayPal" />
                @endif
            @endif
        </native:column>
    </native:scroll-view>
@endif
