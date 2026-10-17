<?php

namespace App\Livewire;

use App\Support\EventData;
use App\Support\NotificationData;
use App\Support\SeriesData;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Home extends Component
{
    public string $name = 'Tommy Jason';

    public bool $isLive = true;

    public int $notifications = 0;

    public array $events = [];

    /** @var array<int, array{name: string, slug: string, description: string, color: string, messages: array<int, array{title: string, pastor: string, minutes: int}>}> */
    public array $series = [];

    /** @var array{text: string, reference: string} */
    public array $verse = [
        'text' => 'For where two or three gather in my name, there am I with them.',
        'reference' => 'Matthew 18:20',
    ];

    /** @var array<int, array{label: string, route: string, icon: string}> */
    public array $shortcuts = [
        ['label' => 'Prayer', 'route' => 'prayer-requests', 'icon' => 'M12 21s-7-4.6-7-10.5A4.2 4.2 0 0 1 12 8a4.2 4.2 0 0 1 7 2.5C19 16.4 12 21 12 21z'],
        ['label' => 'Groups', 'route' => 'small-groups', 'icon' => 'M17 20v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2M10 10a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM21 20v-2a4 4 0 0 0-3-3.9M16 2.1a4 4 0 0 1 0 7.8'],
        ['label' => 'Bible Plan', 'route' => 'bible-reading-plan', 'icon' => 'M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2zM4 19a2 2 0 0 1 2-2h13'],
        ['label' => 'Courses', 'route' => 'course', 'icon' => 'M2 9l10-5 10 5-10 5zM6 11v5c0 1.5 3 3 6 3s6-1.5 6-3v-5'],
    ];

    public function mount(): void
    {
        $this->events = array_slice(array_values(EventData::all()), 0, 2);
        $this->series = array_values(SeriesData::all());
        $this->notifications = NotificationData::unreadCount(session('citylife.read_notifications', []));
    }

    public function greeting(): string
    {
        $hour = (int) now()->format('G');

        return match (true) {
            $hour < 12 => 'Good morning',
            $hour < 18 => 'Good afternoon',
            default => 'Good evening',
        };
    }

    public function render()
    {
        return view('livewire.home');
    }
}
