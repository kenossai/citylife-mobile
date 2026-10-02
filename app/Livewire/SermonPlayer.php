<?php

namespace App\Livewire;

use App\Support\SeriesData;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class SermonPlayer extends Component
{
    public array $series = [];

    public int $number = 1;

    public function mount(string $slug, int $number): void
    {
        $this->series = SeriesData::find($slug) ?? abort(404);
        abort_unless(isset($this->series['messages'][$number - 1]), 404);
        $this->number = $number;
    }

    public function render()
    {
        $messages = $this->series['messages'];

        return view('livewire.sermon-player', [
            'message' => $messages[$this->number - 1],
            'previous' => $this->number > 1 ? $this->number - 1 : null,
            'next' => $this->number < count($messages) ? $this->number + 1 : null,
        ]);
    }
}
