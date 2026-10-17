<?php

namespace App\Livewire;

use App\Support\NotificationData;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Placeholder extends Component
{
    #[Locked]
    public string $title = '';

    #[Locked]
    public string $page = '';

    /** @var array<int, array{id: string, title: string, message: string, time: string, icon: string, read: bool}> */
    #[Locked]
    public array $notifications = [];

    /** @var array<int, int> */
    #[Locked]
    public array $completedReadings = [];

    /** @var array<int, int> */
    #[Locked]
    public array $completedLessons = [];

    /** @var array<int, string> */
    #[Locked]
    public array $interestedGroups = [];

    /** @var array<string, bool> */
    #[Locked]
    public array $settings = [];

    #[Locked]
    public bool $courseStarted = false;

    #[Locked]
    public ?string $openFaq = null;

    /** @var array<int, array{id: int, title: string, duration: string, description: string}> */
    #[Locked]
    public array $lessons = [
        ['id' => 1, 'title' => 'Foundations of faith', 'duration' => '8 min', 'description' => 'Build a strong foundation for your walk with God.'],
        ['id' => 2, 'title' => 'Knowing the Word', 'duration' => '12 min', 'description' => 'Discover how Scripture shapes everyday life.'],
        ['id' => 3, 'title' => 'Life in the Spirit', 'duration' => '10 min', 'description' => 'Explore what it means to live led by the Holy Spirit.'],
    ];

    /** @var array<int, array{day: int, title: string, reference: string}> */
    #[Locked]
    public array $readings = [
        ['day' => 1, 'title' => 'A life rooted in God', 'reference' => 'Psalm 1 · John 1:1–18'],
        ['day' => 2, 'title' => 'The beginning', 'reference' => 'Genesis 1 · John 1:19–34'],
        ['day' => 3, 'title' => 'A promise of hope', 'reference' => 'Psalm 2 · John 1:35–51'],
        ['day' => 4, 'title' => 'Walking with wisdom', 'reference' => 'Proverbs 1 · John 2'],
        ['day' => 5, 'title' => 'A heart of worship', 'reference' => 'Psalm 3 · John 3:1–21'],
        ['day' => 6, 'title' => 'Trusting God', 'reference' => 'Proverbs 2 · John 3:22–36'],
        ['day' => 7, 'title' => 'A new song', 'reference' => 'Psalm 4 · John 4:1–26'],
    ];

    /** @var array<int, array{id: string, name: string, description: string, icon: string}> */
    #[Locked]
    public array $groups = [
        ['id' => 'bible-study', 'name' => 'Bible study', 'description' => 'Grow in Scripture and share questions together.', 'icon' => 'book'],
        ['id' => 'young-adults', 'name' => 'Young adults', 'description' => 'Build friendships and explore faith in everyday life.', 'icon' => 'people'],
        ['id' => 'prayer', 'name' => 'Prayer community', 'description' => 'Encourage one another and make space to pray.', 'icon' => 'heart'],
    ];

    /** @var array<int, array{id: string, question: string, answer: string}> */
    #[Locked]
    public array $faqs = [
        ['id' => 'service', 'question' => 'When does the church meet?', 'answer' => 'Sunday worship is from 10:30 am to 12:00 pm. We would love to welcome you.'],
        ['id' => 'location', 'question' => 'Where can I find City Life?', 'answer' => 'We meet at 1 South Parade, Shalesmoor, Sheffield, S3 8SS.'],
        ['id' => 'online', 'question' => 'Can I watch online?', 'answer' => 'Visit the church website to find the latest online services and messages.'],
    ];

    public function mount(string $title, string $page): void
    {
        $this->title = $title;
        $this->page = $page;
        $readIds = session('citylife.read_notifications', []);
        $this->notifications = array_map(
            fn (array $notification): array => [
                ...$notification,
                'read' => in_array($notification['id'], $readIds, true),
            ],
            array_values(NotificationData::all()),
        );
        $this->completedReadings = array_values(array_intersect(
            session('citylife.completed_readings', []),
            array_column($this->readings, 'day'),
        ));
        $this->completedLessons = array_values(array_intersect(
            session('citylife.completed_lessons', []),
            array_column($this->lessons, 'id'),
        ));
        $this->interestedGroups = array_values(array_intersect(
            session('citylife.interested_groups', []),
            array_column($this->groups, 'id'),
        ));
        $this->courseStarted = session('citylife.course_started', false);
        $this->settings = array_replace([
            'push_notifications' => true,
            'event_reminders' => true,
            'email_updates' => false,
        ], session('citylife.settings', []));
    }

    public function markRead(string $id): void
    {
        if (! array_key_exists($id, NotificationData::all())) {
            return;
        }

        foreach ($this->notifications as $index => $notification) {
            if ($notification['id'] === $id) {
                $this->notifications[$index]['read'] = true;
                break;
            }
        }

        $this->persistReadNotifications();
    }

    public function markAllRead(): void
    {
        foreach ($this->notifications as $index => $notification) {
            $this->notifications[$index]['read'] = true;
        }

        $this->persistReadNotifications();
    }

    public function toggleReading(int $day): void
    {
        if (! in_array($day, array_column($this->readings, 'day'), true)) {
            return;
        }

        $this->completedReadings = $this->toggleValue($this->completedReadings, $day);
        session()->put('citylife.completed_readings', $this->completedReadings);
    }

    public function startCourse(): void
    {
        $this->courseStarted = true;
        session()->put('citylife.course_started', true);
    }

    public function toggleLesson(int $lessonId): void
    {
        if (! $this->courseStarted || ! in_array($lessonId, array_column($this->lessons, 'id'), true)) {
            return;
        }

        $this->completedLessons = $this->toggleValue($this->completedLessons, $lessonId);
        session()->put('citylife.completed_lessons', $this->completedLessons);
    }

    public function toggleGroup(string $groupId): void
    {
        if (! in_array($groupId, array_column($this->groups, 'id'), true)) {
            return;
        }

        $this->interestedGroups = $this->toggleValue($this->interestedGroups, $groupId);
        session()->put('citylife.interested_groups', $this->interestedGroups);
    }

    public function toggleSetting(string $setting): void
    {
        if (! array_key_exists($setting, $this->settings)) {
            return;
        }

        $this->settings[$setting] = ! $this->settings[$setting];
        session()->put('citylife.settings', $this->settings);
    }

    public function toggleFaq(string $faqId): void
    {
        if (! in_array($faqId, array_column($this->faqs, 'id'), true)) {
            return;
        }

        $this->openFaq = $this->openFaq === $faqId ? null : $faqId;
    }

    /** @param array<int, int|string> $values
     * @return array<int, int|string>
     */
    private function toggleValue(array $values, int|string $value): array
    {
        if (in_array($value, $values, true)) {
            return array_values(array_filter($values, fn (int|string $item): bool => $item !== $value));
        }

        $values[] = $value;

        return $values;
    }

    private function persistReadNotifications(): void
    {
        $readIds = array_column(
            array_filter($this->notifications, fn (array $notification): bool => $notification['read']),
            'id',
        );
        $readIds = array_values(array_intersect(array_keys(NotificationData::all()), $readIds));

        session()->put('citylife.read_notifications', $readIds);
    }

    public function render()
    {
        return view('livewire.placeholder');
    }
}
