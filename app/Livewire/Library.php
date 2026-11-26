<?php

namespace App\Livewire;

use App\Support\ContentApi;
use App\Support\LibraryStore;
use App\Support\SeriesData;
use App\Support\SermonMedia;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Library extends Component
{
    public string $tab = 'sermons';

    public bool $available = true;

    public function showTab(string $tab): void
    {
        if (in_array($tab, ['sermons', 'books', 'playlists', 'favourites'], true)) {
            $this->tab = $tab;
        }
    }

    /**
     * Pick a random sermon that can be played, and open it the same way tapping it in the list would.
     */
    public function shuffle(): void
    {
        $playable = array_values(array_filter($this->sermons(), fn (array $sermon) => filled($sermon['video_url'])));

        if ($playable === []) {
            return;
        }

        $pick = $playable[array_rand($playable)];
        $target = SermonMedia::watchTarget($pick['id'], $pick['video_url']);

        $this->redirect($target['url'], navigate: ! $target['native']);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function sermons(): array
    {
        return array_map(fn (array $sermon): array => [
            'id' => $sermon['id'],
            'title' => $sermon['title'],
            'pastor' => $sermon['speaker'] ?: 'City Life Church',
            'date' => $sermon['preached_at'] ?? null,
            'series' => $sermon['series']['title'] ?? '',
            'minutes' => SeriesData::minutes($sermon['duration'] ?? null),
            'color' => $sermon['series']['color'] ?? '#292929',
            'image' => $sermon['thumbnail_url'] ?? null,
            'video_url' => $sermon['video_url'] ?? null,
        ], ContentApi::get('sermons') ?? []);
    }

    /**
     * @param  array<int, int>  $ids
     * @return array<int, array<string, mixed>>
     */
    private function sermonsWithIds(array $ids): array
    {
        $byId = collect($this->sermons())->keyBy('id');

        return collect($ids)->map(fn (int $id) => $byId->get($id))->filter()->values()->all();
    }

    public function render()
    {
        $this->available = ContentApi::isConfigured() && ContentApi::get($this->tab === 'books' ? 'books' : 'sermons') !== null;

        return view('livewire.library', [
            'recent' => $this->tab === 'sermons' ? $this->sermonsWithIds(LibraryStore::recent()) : [],
            'favourites' => $this->tab === 'favourites' ? $this->sermonsWithIds(LibraryStore::favourites()) : [],
            'books' => $this->tab === 'books' ? (ContentApi::get('books') ?? []) : [],
            'playlists' => $this->tab === 'playlists' ? array_values(SeriesData::all()) : [],
        ]);
    }
}
