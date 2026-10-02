<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class PrayerRequests extends Component
{
    public string $tab = 'browse';

    public string $name = '';

    public string $category = 'Personal';

    public string $request = '';

    /** @var array<int, string> */
    public array $categories = ['Personal', 'Family', 'Health', 'Spiritual', 'Work', 'Church', 'Praise Report'];

    /** @var array<int, array{id: int, name: string, date: string, category: string, text: string, prayers: int, prayed: bool}> */
    public array $requests = [
        ['id' => 1, 'name' => 'Sarah Johnson', 'date' => 'Jan 9, 2026', 'category' => 'Health', 'text' => 'Please pray for my father who is scheduled for surgery tomorrow. Praying for the doctors and a successful procedure.', 'prayers' => 45, 'prayed' => false],
        ['id' => 2, 'name' => 'Michael Chen', 'date' => 'Jan 8, 2026', 'category' => 'Spiritual', 'text' => 'Requesting prayers for strength and wisdom as I navigate a difficult decision in my life.', 'prayers' => 23, 'prayed' => false],
        ['id' => 3, 'name' => 'David Kim', 'date' => 'Jan 7, 2026', 'category' => 'Praise Report', 'text' => 'Thank God for answering our prayers for our new home!', 'prayers' => 21, 'prayed' => false],
    ];

    /** @var array<int, int> */
    public array $mine = [];

    /** @var array<int, string> */
    public array $guidelines = [
        'Be respectful and considerate in your requests',
        'Share authentic needs but protect privacy',
        'Come back to share praise reports when prayers are answered',
    ];

    public function showTab(string $tab): void
    {
        if (in_array($tab, ['browse', 'mine', 'submit'], true)) {
            $this->tab = $tab;
        }
    }

    public function submit(): void
    {
        $this->validate([
            'name' => ['nullable', 'string', 'max:60'],
            'category' => ['required', 'in:'.implode(',', $this->categories)],
            'request' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $id = count($this->requests) + 1;

        array_unshift($this->requests, [
            'id' => $id,
            'name' => trim($this->name) !== '' ? trim($this->name) : 'Anonymous',
            'date' => now()->format('M j, Y'),
            'category' => $this->category,
            'text' => trim($this->request),
            'prayers' => 0,
            'prayed' => false,
        ]);
        $this->mine[] = $id;

        $this->reset('name', 'request');
        $this->category = 'Personal';
        $this->tab = 'mine';
    }

    public function pray(int $id): void
    {
        foreach ($this->requests as $index => $prayerRequest) {
            if ($prayerRequest['id'] === $id && ! $prayerRequest['prayed']) {
                $this->requests[$index]['prayed'] = true;
                $this->requests[$index]['prayers']++;
            }
        }
    }

    public function render()
    {
        return view('livewire.prayer-requests');
    }
}
