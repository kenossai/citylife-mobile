<?php

namespace App\NativeComponents;

use App\Support\ContentApi;
use App\Support\EventData;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class NativeEvents extends NativeComponent
{
    /** @var array<int, array<string, mixed>> */
    public array $events = [];

    public bool $failed = false;

    public function mount(): void
    {
        $this->load();
    }

    public function refreshEvents(): void
    {
        ContentApi::refresh();
        $this->load();
    }

    public function open(int $id): void
    {
        $this->navigate("/native/events/{$id}");
    }

    public function goHome(): void
    {
        $this->exitToWeb('/home');
    }

    private function load(): void
    {
        $this->events = array_values(EventData::all());
        $this->failed = ContentApi::isConfigured() && $this->events === [] && ContentApi::isDown();
    }

    public function render(): View
    {
        return view('native.native-events');
    }
}
