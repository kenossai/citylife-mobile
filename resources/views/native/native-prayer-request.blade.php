<native:top-bar title="Prayer request" back />

<native:scroll-view class="w-full h-full bg-theme-background">
    <native:column class="w-full gap-4 p-4">
        @if ($sent)
            <native:column class="w-full items-center gap-3 p-6 rounded-lg bg-theme-surface">
                <native:icon ios="checkmark.circle.fill" android="check_circle" :size="48" class="text-theme-success" />
                <native:text class="text-xl font-bold text-theme-on-surface">Thank you</native:text>
                <native:text class="text-theme-on-surface-variant text-center">
                    {{ $isPrivate ? 'Your request has gone to our pastoral team.' : 'Your request will appear once our team has reviewed it.' }}
                </native:text>
                <native:button label="Send another" class="w-full" @press="again" />
                <native:button label="See all requests" variant="ghost" class="w-full" @press="goHome" />
            </native:column>
        @else
            <native:text class="text-theme-on-surface-variant">Share what is on your heart. Our team will pray with you.</native:text>

            <native:outlined-text-input class="w-full" label="Your name (optional)" placeholder="Leave blank to stay anonymous"
                native:model.blur="name" autocapitalize="words" content-type="name" />

            <native:select class="w-full" label="Category" :options="$categories" native:model="category" />

            <native:outlined-text-input class="w-full" label="Prayer request" placeholder="Share what is on your heart…"
                native:model.debounce.300ms="request" multiline :minLines="4" :maxLength="1000" @submit="send" />

            <native:row class="w-full items-center gap-3">
                <native:toggle native:model="isPrivate" />
                <native:text class="flex-1 text-theme-on-surface">Keep this private (pastoral team only)</native:text>
            </native:row>

            @if ($error)
                <native:text class="text-theme-destructive">{{ $error }}</native:text>
            @endif

            <native:button label="Send request" class="w-full" :loading="$sending" @press="send" />
        @endif
    </native:column>
</native:scroll-view>
