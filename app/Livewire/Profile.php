<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Profile extends Component
{
    public string $name = 'John Doe';

    public string $memberSince = 'March 2023';

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

    public function togglePreference(string $preference): void
    {
        if (array_key_exists($preference, $this->preferences)) {
            $this->preferences[$preference] = ! $this->preferences[$preference];
        }
    }

    public function render()
    {
        return view('livewire.profile');
    }
}
