<?php

namespace App\Livewire;

use App\Support\EventData;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Events extends Component
{
    public string $category = 'All';

    public array $categories = ['All', 'Services', 'Study', 'Youth', 'Community'];

    public array $events = [];

    public function mount(): void
    {
        $this->events = array_values(EventData::all());
    }

    #[Computed]
    public function filtered(): array
    {
        return $this->category === 'All'
            ? $this->events
            : array_values(array_filter($this->events, fn ($e) => $e['category'] === $this->category));
    }

    public function render()
    {
        return view('livewire.events');
    }
}
