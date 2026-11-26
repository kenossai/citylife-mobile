<?php

namespace App\Livewire;

use App\Livewire\Concerns\OpensExternalLinks;
use App\Support\ContentApi;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class BookDetail extends Component
{
    use OpensExternalLinks;

    /** @var array<string, mixed> */
    public array $book = [];

    public function mount(string $slug): void
    {
        $this->book = ContentApi::get('books/'.$slug) ?? abort(404);
    }

    public function openAmazon(): void
    {
        $this->openExternal($this->book['amazon_url'] ?? null);
    }

    public function openKindle(): void
    {
        $this->openExternal($this->book['kindle_url'] ?? null);
    }

    public function render()
    {
        return view('livewire.book-detail');
    }
}
