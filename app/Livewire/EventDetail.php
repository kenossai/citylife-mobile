<?php

namespace App\Livewire;

use App\Support\EventData;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class EventDetail extends Component
{
    public array $event = [];

    public bool $registered = false;

    public function mount(int $id): void
    {
        $this->event = EventData::find($id) ?? abort(404);
    }

    public function toggleRegistration(): void
    {
        $this->registered = ! $this->registered;
    }

    public function render()
    {
        return view('livewire.event-detail');
    }
}
