<?php

namespace App\Livewire;

use App\Support\ContentApi;
use App\Support\EventData;
use App\Support\MemberSession;
use App\Support\NotificationData;
use App\Support\SeriesData;
use App\Support\SermonMedia;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Home extends Component
{
    public string $name = 'Tommy Jason';

    public bool $isLive = true;

    public string $liveTitle = 'Sunday Service';

    public string $liveSubtitle = 'Join us in worship';

    public ?int $liveId = null;

    public ?string $liveVideo = null;

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
        ['label' => 'Library', 'route' => 'library', 'icon' => 'M4 4v16M9 4v16M14 6l4 14M14 6l3-1 3 1'],
        ['label' => 'Courses', 'route' => 'course', 'icon' => 'M2 9l10-5 10 5-10 5zM6 11v5c0 1.5 3 3 6 3s6-1.5 6-3v-5'],
    ];

    public function mount(): void
    {
        if (ContentApi::isConfigured()) {
            $this->name = MemberSession::member()['first_name'] ?? 'Friend';
        }

        $home = ContentApi::get('home');

        if ($home === null && ContentApi::isConfigured()) {
            $this->isLive = false;
        }

        if ($home !== null) {
            $live = $home['live'] ?? null;
            $this->isLive = filled($live);
            $this->liveTitle = $live['title'] ?? $this->liveTitle;
            $this->liveSubtitle = filled($live['speaker'] ?? null) ? 'with '.$live['speaker'] : $this->liveSubtitle;
            $this->liveId = isset($live['id']) ? (int) $live['id'] : null;
            $this->liveVideo = $live['video_url'] ?? null;
        }

        $verse = $home['verse'] ?? null;

        if (filled($verse['text'] ?? null) && filled($verse['reference'] ?? null)) {
            $this->verse = ['text' => $verse['text'], 'reference' => $verse['reference']];
        }

        if (ContentApi::isConfigured()) {
            $this->events = array_map(EventData::fromApi(...), array_slice($home['events'] ?? [], 0, 2));
            $this->series = array_map(SeriesData::summaryFromApi(...), $home['series'] ?? []);
        } else {
            $this->events = array_slice(array_values(EventData::all()), 0, 2);
            $this->series = array_values(SeriesData::all());
        }
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

    /**
     * Where the Watch button on the live card goes. On the phone a YouTube stream opens the native player, because the web screens
     * cannot embed YouTube (the app shell sends it to the browser); anywhere else it opens the sermon page, and with no playable
     * video it falls back to the sermon list.
     *
     * @return array{url: string, native: bool}
     */
    public function watchTarget(): array
    {
        $media = SermonMedia::from($this->liveVideo);

        if (! $this->liveId || ! $media) {
            return ['url' => route('sermons'), 'native' => false];
        }

        return SermonMedia::watchTarget($this->liveId, $this->liveVideo);
    }

    public function render()
    {
        return view('livewire.home', ['watch' => $this->isLive ? $this->watchTarget() : null]);
    }
}
