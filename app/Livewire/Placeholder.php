<?php

namespace App\Livewire;

use App\Support\ContentApi;
use App\Support\MemberSession;
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

    /** @var array<string, bool> */
    #[Locked]
    public array $settings = [];

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
        $this->settings = array_replace([
            'push_notifications' => true,
            'event_reminders' => true,
            'email_updates' => false,
        ], session('citylife.settings', []));

        if ($page === 'app-settings' && ContentApi::isConfigured() && MemberSession::check()) {
            $server = MemberSession::request('GET', 'me/preferences')['data'] ?? null;

            if ($server !== null) {
                $this->settings['email_updates'] = (bool) $server['email_newsletters'];
            }
        }
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

    public function toggleSetting(string $setting): void
    {
        if (! array_key_exists($setting, $this->settings)) {
            return;
        }

        $this->settings[$setting] = ! $this->settings[$setting];

        if ($setting === 'email_updates' && ContentApi::isConfigured() && MemberSession::check()) {
            $result = MemberSession::request('PATCH', 'me/preferences', ['email_newsletters' => $this->settings[$setting]]);

            if (! $result['ok']) {
                $this->settings[$setting] = ! $this->settings[$setting];

                return;
            }
        }

        session()->put('citylife.settings', $this->settings);
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
