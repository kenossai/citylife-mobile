<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Placeholder extends Component
{
    public string $title = '';

    public function mount(string $title): void
    {
        $this->title = $title;
    }

    public function render()
    {
        return view('livewire.placeholder');
    }
}
