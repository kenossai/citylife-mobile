<?php

namespace App\Livewire;

use App\Support\ContentApi;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class ReadingPlan extends Component
{
    /** @var array{id: int, title: string, description: ?string, days: array<int, array{day: int, title: string, reference: string}>}|null */
    public ?array $plan = null;

    public bool $available = true;

    /** @var array<int, int> */
    public array $completed = [];

    public function mount(): void
    {
        if (ContentApi::isConfigured()) {
            $plan = ContentApi::get('reading-plan');

            $this->available = $plan !== null || ContentApi::get('church') !== null;
            $this->plan = filled($plan) ? $plan : null;
        } else {
            $this->plan = self::demoPlan();
        }

        if ($this->plan) {
            $this->completed = array_values(array_intersect(
                session("citylife.reading_progress.{$this->plan['id']}", []),
                array_column($this->plan['days'], 'day'),
            ));
        }
    }

    public function toggle(int $day): void
    {
        if (! $this->plan || ! in_array($day, array_column($this->plan['days'], 'day'), true)) {
            return;
        }

        $this->completed = in_array($day, $this->completed, true)
            ? array_values(array_diff($this->completed, [$day]))
            : [...$this->completed, $day];

        session()->put("citylife.reading_progress.{$this->plan['id']}", $this->completed);
    }

    /** @return array<string, mixed> */
    private static function demoPlan(): array
    {
        return [
            'id' => 0,
            'title' => 'Start your day in the Word',
            'description' => 'A simple 7-day plan.',
            'days' => [
                ['day' => 1, 'title' => 'A life rooted in God', 'reference' => 'Psalm 1 · John 1:1–18'],
                ['day' => 2, 'title' => 'The beginning', 'reference' => 'Genesis 1 · John 1:19–34'],
                ['day' => 3, 'title' => 'A promise of hope', 'reference' => 'Psalm 2 · John 1:35–51'],
            ],
        ];
    }

    public function render()
    {
        return view('livewire.reading-plan');
    }
}
