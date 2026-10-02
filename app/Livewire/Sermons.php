<?php

namespace App\Livewire;

use App\Support\SeriesData;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Sermons extends Component
{
    public string $tab = 'recent';

    public string $search = '';

    /** @var array<int, array{id: int, title: string, pastor: string, date: string, series: string, minutes: int, views: int, color: string}> */
    public array $sermons = [
        ['id' => 1, 'title' => 'Walking in Faith', 'pastor' => 'Pastor John Smith', 'date' => '2025-12-29', 'series' => 'Faith Journey', 'minutes' => 45, 'views' => 1240, 'color' => '#1f2937'],
        ['id' => 2, 'title' => 'The Power of Prayer', 'pastor' => 'Pastor Sarah Johnson', 'date' => '2025-12-22', 'series' => 'Prayer Life', 'minutes' => 38, 'views' => 2310, 'color' => '#9a6b5a'],
        ['id' => 3, 'title' => 'Living with Purpose', 'pastor' => 'Pastor John Smith', 'date' => '2025-12-15', 'series' => 'Faith Journey', 'minutes' => 42, 'views' => 980, 'color' => '#111827'],
        ['id' => 4, 'title' => 'Grace That Restores', 'pastor' => 'Pastor Sarah Johnson', 'date' => '2025-12-08', 'series' => 'Prayer Life', 'minutes' => 40, 'views' => 1675, 'color' => '#4b5563'],
    ];

    public function showTab(string $tab): void
    {
        if (in_array($tab, ['recent', 'series', 'popular'], true)) {
            $this->tab = $tab;
        }
    }

    /** @return array<int, array<string, mixed>> */
    #[Computed]
    public function results(): array
    {
        $term = mb_strtolower(trim($this->search));

        $sermons = array_filter($this->sermons, fn (array $sermon) => $term === ''
            || str_contains(mb_strtolower($sermon['title'].' '.$sermon['pastor'].' '.$sermon['series']), $term));

        return match ($this->tab) {
            'popular' => collect($sermons)->sortByDesc('views')->values()->all(),
            default => collect($sermons)->sortByDesc('date')->values()->all(),
        };
    }

    /** @return array<int, array{name: string, slug: string, description: string, color: string, messages: array<int, array<string, mixed>>}> */
    #[Computed]
    public function seriesList(): array
    {
        $term = mb_strtolower(trim($this->search));

        return array_values(array_filter(SeriesData::all(), fn (array $series) => $term === ''
            || str_contains(mb_strtolower($series['name'].' '.$series['description']), $term)));
    }

    public function render()
    {
        return view('livewire.sermons');
    }
}
