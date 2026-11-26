<?php

namespace App\Livewire;

use App\Livewire\Concerns\PicksBibleVersion;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class BibleBooks extends Component
{
    use PicksBibleVersion;

    public string $testament = 'new_testament';

    public string $search = '';

    /** @var array<int, array{id: string, title: string, abbreviation: ?string, canon: ?string, chapters: int}> */
    public array $books = [];

    /** @var array{book: string, chapter: int}|null */
    public ?array $last = null;

    public function mount(): void
    {
        $this->loadVersions();
        $this->books = $this->loadBooks();
        $this->last = session('citylife.bible_last');

        $lastBook = collect($this->books)->firstWhere('id', $this->last['book'] ?? null);

        if ($lastBook && filled($lastBook['canon'])) {
            $this->testament = $lastBook['canon'];
        }
    }

    public function selectVersion(int $id): void
    {
        if ($this->rememberVersion($id)) {
            $this->books = $this->loadBooks();
        }
    }

    /** @return array<int, array{id: string, title: string, abbreviation: ?string, canon: ?string, chapters: int}> */
    #[Computed]
    public function visibleBooks(): array
    {
        $term = mb_strtolower(trim($this->search));

        return array_values(array_filter($this->books, fn (array $book) => $term !== ''
            ? str_contains(mb_strtolower($book['title']), $term)
            : $book['canon'] === $this->testament));
    }

    /** @return array<string, string> */
    #[Computed]
    public function lastTitle(): ?string
    {
        $book = collect($this->books)->firstWhere('id', $this->last['book'] ?? null);

        return $book ? $book['title'].' '.$this->last['chapter'] : null;
    }

    public function render()
    {
        return view('livewire.bible-books');
    }
}
