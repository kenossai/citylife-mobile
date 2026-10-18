<?php

namespace App\Livewire;

use App\Support\ContentApi;
use App\Support\MemberSession;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Native\Mobile\Facades\Browser;

#[Layout('components.layouts.main')]
class Profile extends Component
{
    public string $name = 'John Doe';

    public string $memberSince = 'March 2023';

    public bool $usesWebsite = false;

    public string $subtitle = 'Member since March 2023';

    /** @var array<string, string> */
    public array $contact = [
        'Email' => 'john.doe@example.com',
        'Phone Number' => '+1 (555) 123-4567',
        'Address' => '123 Church Street, City, State 12345',
    ];

    /** @var array<string, string> */
    public array $membership = [
        'Member ID' => 'CM2023-0152',
        'Joined Date' => 'March 15, 2023',
        'Ministry Group' => 'Youth Ministry',
    ];

    /** @var array<string, bool> */
    public array $preferences = [
        'pushNotifications' => true,
        'emailNewsletters' => true,
        'eventReminders' => false,
    ];

    public function mount()
    {
        if (! ContentApi::isConfigured()) {
            return null;
        }

        $this->usesWebsite = true;
        $stored = session('citylife.settings', []);
        $this->preferences['pushNotifications'] = $stored['push_notifications'] ?? true;
        $this->preferences['eventReminders'] = $stored['event_reminders'] ?? true;

        $member = MemberSession::member();

        if (! $member) {
            return $this->redirectRoute('login', navigate: true);
        }

        $since = filled($member['member_since'] ?? null) ? strtotime($member['member_since']) : null;

        $server = MemberSession::request('GET', 'me/preferences')['data'] ?? null;
        $this->preferences['emailNewsletters'] = (bool) ($server['email_newsletters'] ?? false);

        $this->name = $member['name'];
        $this->memberSince = $since ? date('F Y', $since) : '';
        $this->subtitle = MemberSession::subtitle($member);
        $this->contact = array_filter([
            'Email' => $member['email'] ?? null,
            'Phone Number' => $member['phone'] ?? null,
        ]);
        $this->membership = array_filter([
            'Member ID' => $member['membership_number'] ?? null,
            'Status' => MemberSession::statusLabel($member),
            'Member Since' => $since ? date('F j, Y', $since) : null,
        ]);

        return null;
    }

    public function openPasswordReset()
    {
        $url = rtrim(config('services.citylife.url'), '/').'/member/forgot-password';

        return Browser::inApp($url) ? null : redirect()->away($url);
    }

    public function togglePreference(string $preference): void
    {
        if (! array_key_exists($preference, $this->preferences)) {
            return;
        }

        $this->preferences[$preference] = ! $this->preferences[$preference];

        if (! $this->usesWebsite) {
            return;
        }

        if ($preference === 'emailNewsletters') {
            $result = MemberSession::request('PATCH', 'me/preferences', ['email_newsletters' => $this->preferences[$preference]]);

            if (! $result['ok']) {
                $this->preferences[$preference] = ! $this->preferences[$preference];
            }

            return;
        }

        $settings = session('citylife.settings', []);
        $key = $preference === 'pushNotifications' ? 'push_notifications' : 'event_reminders';
        session()->put('citylife.settings', [...$settings, $key => $this->preferences[$preference]]);
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
