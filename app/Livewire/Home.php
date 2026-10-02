<?php

namespace App\Livewire;

use App\Support\EventData;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Home extends Component
{
    public string $name = 'Tommy Jason';

    public bool $isLive = true;

    public int $notifications = 1;

    public array $events = [];

    public function mount(): void
    {
        $this->events = array_slice(array_values(EventData::all()), 0, 2);
    }

    public function render()
    {
        return view('livewire.home');
    }
}
