<?php

namespace App\Livewire;

use App\Livewire\Concerns\PicksBibleVersion;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class BibleChapters extends Component
{
    use PicksBibleVersion;

    /** @var array{id: string, title: string, abbreviation: ?string, canon: ?string, chapters: int} */
    public array $info = [];

    public function mount(string $book): void
    {
        $this->loadVersions();

        abort_if($this->unavailable, 404);

        $this->info = collect($this->loadBooks())->firstWhere('id', strtoupper($book)) ?? abort(404);
    }

    public function render()
    {
        return view('livewire.bible-chapters');
    }
}
