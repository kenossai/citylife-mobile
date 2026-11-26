<?php

namespace App\Livewire;

use App\Support\ContentApi;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class ReadingPassage extends Component
{
    /** @var array{id: int, title: string, days: array<int, array{day: int, title: string, reference: string}>} */
    public array $plan = [];

    /** @var array{day: int, title: string, reference: string} */
    public array $reading = [];

    /** @var array<string, mixed>|null */
    public ?array $text = null;

    public bool $completed = false;

    public function mount(int $day): void
    {
        $this->plan = ContentApi::get('reading-plan') ?: abort(404);

        $reading = collect($this->plan['days'])->firstWhere('day', $day);

        abort_if($reading === null, 404);

        $this->reading = $reading;
        $this->completed = in_array($day, session("citylife.reading_progress.{$this->plan['id']}", []), true);
        $this->loadText();
    }

    private function loadText(): void
    {
        $this->text = ContentApi::get($this->passagePath(), 86400);
    }

    private function passagePath(): string
    {
        return 'passages?reference='.rawurlencode($this->reading['reference']);
    }

    public function retry(): void
    {
        ContentApi::forget($this->passagePath());
        $this->loadText();
    }

    public function toggleDone(): void
    {
        $key = "citylife.reading_progress.{$this->plan['id']}";
        $done = session($key, []);

        $done = in_array($this->reading['day'], $done, true)
            ? array_values(array_diff($done, [$this->reading['day']]))
            : [...$done, $this->reading['day']];

        session()->put($key, $done);
        $this->completed = in_array($this->reading['day'], $done, true);
    }

    public function render()
    {
        $days = array_column($this->plan['days'], 'day');
        $position = array_search($this->reading['day'], $days, true);

        return view('livewire.reading-passage', [
            'previous' => $position > 0 ? $days[$position - 1] : null,
            'next' => $days[$position + 1] ?? null,
        ]);
    }
}
