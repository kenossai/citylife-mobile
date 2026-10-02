<?php

namespace App\Livewire;

use App\Support\SeriesData;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class SeriesDetail extends Component
{
    public array $series = [];

    public function mount(string $slug): void
    {
        $this->series = SeriesData::find($slug) ?? abort(404);
    }

    public function render()
    {
        return view('livewire.series-detail', [
            'average' => (int) round(collect($this->series['messages'])->avg('minutes')),
        ]);
    }
}
