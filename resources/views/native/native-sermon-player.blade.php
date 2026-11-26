<native:top-bar :title="$sermon['title'] ?? 'Sermon'" back />

@if ($sermon === [])
    <native:column class="w-full h-full items-center justify-center gap-3 p-8 bg-theme-background">
        <native:text class="text-lg font-bold text-theme-on-surface">We can't load this sermon right now</native:text>
        <native:button label="Close" @press="close" />
    </native:column>
@else
    <native:scroll-view class="w-full h-full bg-theme-background">
        <native:column class="w-full gap-4 p-4">
            @if ($media && in_array($media['type'], ['youtube', 'video', 'audio'], true))
                <native:webview :src="$media['src']" javascript dom-storage class="w-full h-[230dp] rounded-lg" />
            @elseif ($media)
                <native:button label="Watch this message" class="w-full" @press="openLink" />
            @else
                <native:text class="text-theme-on-surface-variant">The recording for this message isn't available yet.</native:text>
            @endif

            <native:text class="text-2xl font-bold text-theme-on-surface">{{ $sermon['title'] }}</native:text>

            <native:text class="text-theme-on-surface-variant">
                {{ $sermon['speaker'] ?: 'City Life Church' }}@if ($sermon['preached_at'] ?? null) · {{ \Illuminate\Support\Carbon::parse($sermon['preached_at'])->format('j M Y') }}@endif
            </native:text>

            @if ($sermon['scripture'] ?? [])
                <native:text class="font-bold text-theme-primary">{{ implode(' · ', $sermon['scripture']) }}</native:text>
            @endif

            @if ($sermon['description'] ?? null)
                <native:text class="text-theme-on-surface">{{ strip_tags($sermon['description']) }}</native:text>
            @endif

            <native:button label="Back to sermon" variant="ghost" class="w-full" @press="close" />
        </native:column>
    </native:scroll-view>
@endif
