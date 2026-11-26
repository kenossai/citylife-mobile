<native:top-bar :title="$sermon['title'] ?? 'Sermon'" back />

@if ($sermon === [])
    <native:column class="w-full h-full items-center justify-center gap-3 p-8 bg-theme-background">
        <native:text class="text-lg font-bold text-theme-on-surface">We can't load this sermon right now</native:text>
        <native:button label="Close" @press="close" />
    </native:column>
@else
    <native:scroll-view class="w-full h-full bg-theme-background">
        <native:column class="w-full gap-4 pb-6">
            @if ($media && in_array($media['type'], ['youtube', 'video', 'audio'], true))
                <native:webview :src="$playerSrc" javascript dom-storage class="w-full h-[230dp] bg-black" />
            @elseif ($media)
                <native:column class="w-full p-4">
                    <native:button label="Watch this message" class="w-full" @press="openLink" />
                </native:column>
            @else
                <native:text class="px-4 text-theme-on-surface-variant">The recording for this message isn't available yet.</native:text>
            @endif

            <native:column class="w-full gap-2 px-4">
                <native:row class="w-full items-center">
                    <native:text class="flex-1 text-theme-on-surface-variant">Sermon</native:text>
                    <native:pressable @press="toggleFavourite" a11y-label="Save to favourites" class="p-2">
                        <native:icon ios="{{ $isFavourite ? 'heart.fill' : 'heart' }}" android="{{ $isFavourite ? 'favorite' : 'favorite_border' }}" :size="26" class="text-theme-primary" />
                    </native:pressable>
                    <native:button label="Share" variant="ghost" @press="share" />
                </native:row>

                <native:text class="text-3xl font-bold text-theme-on-surface">{{ $sermon['title'] }}</native:text>

                <native:text class="font-bold text-theme-on-surface">
                    {{ $sermon['speaker'] ?: 'City Life Church' }}@if ($sermon['preached_at'] ?? null) · {{ \Illuminate\Support\Carbon::parse($sermon['preached_at'])->format('j F Y') }}@endif
                </native:text>

                @if ($sermon['scripture'] ?? [])
                    <native:text class="font-bold text-theme-primary">{{ implode(' · ', $sermon['scripture']) }}</native:text>
                @endif
            </native:column>

            @if ($description !== '')
                <native:column class="w-full gap-2 p-4 bg-theme-surface">
                    <native:text class="text-xl font-bold text-theme-on-surface">About this sermon</native:text>
                    <native:text class="text-theme-on-surface-variant">{{ $shortDescription }}</native:text>
                    @if ($canReadMore)
                        <native:button :label="$readMore ? 'Show less' : 'Read more'" variant="ghost" @press="toggleReadMore" />
                    @endif
                </native:column>
            @endif
        </native:column>
    </native:scroll-view>
@endif
