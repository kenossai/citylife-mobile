<?php

namespace App\NativeComponents;

use App\Support\ChurchData;
use App\Support\EventData;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;
use Native\Mobile\Facades\Browser;

class NativeEventDetail extends NativeComponent
{
    /** @var array<string, mixed> */
    public array $event = [];

    /** @var array<string, mixed> */
    public array $giving = [];

    public function mount(): void
    {
        $this->event = EventData::find((int) $this->param('id')) ?? [];

        if (($this->event['requires_payment'] ?? false) === true) {
            $this->giving = ChurchData::get()['giving'];
        }
    }

    public function payByCard(): void
    {
        $this->openLink($this->giving['card_url'] ?? null);
    }

    public function payWithPayPal(): void
    {
        $this->openLink($this->giving['paypal_url'] ?? null);
    }

    private function openLink(?string $url): void
    {
        if (filled($url) && preg_match('~^https?://~i', $url)) {
            Browser::inApp($url);
        }
    }

    public function render(): View
    {
        return view('native.native-event-detail');
    }
}
