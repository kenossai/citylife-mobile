<?php

namespace App\Livewire;

use App\Livewire\Concerns\PicksBibleVersion;
use App\Support\ContentApi;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class BibleReader extends Component
{
    use PicksBibleVersion;

    private const MIN_FONT = 15;

    private const MAX_FONT = 28;

    /** @var array{id: string, title: string, abbreviation: ?string, canon: ?string, chapters: int} */
    public array $info = [];

    public int $chapterNumber = 1;

    public int $fontSize = 18;

    /** @var array<string, mixed>|null */
    public ?array $passage = null;

    public function mount(string $book, int $chapter)
    {
        $this->loadVersions();

        abort_if($this->unavailable, 404);

        $this->info = collect($this->loadBooks())->firstWhere('id', strtoupper($book)) ?? abort(404);

        abort_if($chapter < 1 || $chapter > $this->info['chapters'], 404);

        $this->chapterNumber = $chapter;
        $this->fontSize = max(self::MIN_FONT, min(self::MAX_FONT, (int) session('citylife.bible_font', 18)));
        $this->loadPassage();

        session()->put('citylife.bible_last', ['book' => $this->info['id'], 'chapter' => $chapter]);
    }

    private function loadPassage(): void
    {
        $this->passage = ContentApi::get("bible/{$this->versionId}/books/{$this->info['id']}/chapters/{$this->chapterNumber}", 3600);
    }

    public function selectVersion(int $id)
    {
        if (! $this->rememberVersion($id)) {
            return null;
        }

        $books = collect($this->loadBooks());
        $book = $books->firstWhere('id', $this->info['id']);

        if (! $book || $this->chapterNumber > $book['chapters']) {
            return $this->redirectRoute('bible', navigate: true);
        }

        $this->info = $book;
        $this->loadPassage();

        return null;
    }

    public function retry(): void
    {
        ContentApi::forget("bible/{$this->versionId}/books/{$this->info['id']}/chapters/{$this->chapterNumber}");
        $this->loadPassage();
    }

    public function resize(int $step): void
    {
        $this->fontSize = max(self::MIN_FONT, min(self::MAX_FONT, $this->fontSize + $step));
        session()->put('citylife.bible_font', $this->fontSize);
    }

    public function render()
    {
        return view('livewire.bible-reader', [
            'fontsUrl' => rtrim((string) config('services.citylife.url'), '/').'/api/v1/bible/fonts.css',
        ]);
    }
}
