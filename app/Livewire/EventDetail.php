<?php

namespace App\Livewire;

use App\Support\ChurchData;
use App\Support\EventData;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.main')]
class EventDetail extends Component
{
    public array $event = [];

    /** @var array<string, mixed> */
    #[Locked]
    public array $giving = [];

    public function mount(int $id): void
    {
        $this->event = EventData::find($id) ?? abort(404);

        if ($this->event['requires_payment'] ?? false) {
            $this->giving = ChurchData::get()['giving'];
        }
    }

    public function openCard()
    {
        return ChurchData::open($this->giving['card_url'] ?? null);
    }

    public function openPayPal()
    {
        return ChurchData::open($this->giving['paypal_url'] ?? null);
    }

    public function render()
    {
        return view('livewire.event-detail');
    }
}
