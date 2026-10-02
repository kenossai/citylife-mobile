<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Splash extends Component
{
    public function continue()
    {
        return $this->redirectRoute('home', navigate: true);
    }

    public function render()
    {
        return view('livewire.splash');
    }
}
