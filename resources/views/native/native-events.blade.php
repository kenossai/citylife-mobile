<native:top-bar title="Events" />

<native:column class="w-full h-full bg-theme-background">
    <native:list class="w-full flex-1" separator on-refresh="refreshEvents">
        @forelse ($events as $event)
            <native:list-item
                native:key="event-{{ $event['id'] }}"
                :overline="$event['date']"
                :headline="$event['title']"
                :supporting="$event['time'].' · '.$event['place']"
                :trailingText="$event['category']"
                ios-icon="calendar"
                android-icon="calendar_today"
                @press="open({{ $event['id'] }})"
            />
        @empty
            <native:column class="w-full items-center gap-2 p-8">
                <native:text class="text-lg font-bold text-theme-on-surface">
                    {{ $failed ? "Can't load events right now" : 'No upcoming events' }}
                </native:text>
                <native:text class="text-theme-on-surface-variant text-center">
                    {{ $failed ? 'Pull down to try again.' : 'Check back soon.' }}
                </native:text>
            </native:column>
        @endforelse
    </native:list>

    <native:button variant="ghost" label="Back to home" class="w-full" @press="goHome" />
</native:column>
