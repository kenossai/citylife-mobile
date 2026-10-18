<?php

namespace App\Livewire;

use App\Support\ChurchData;
use App\Support\ContentApi;
use App\Support\MemberSession;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Native\Mobile\Facades\Share;
use Native\Mobile\Facades\System;

#[Layout('components.layouts.main')]
class ChurchInfo extends Component
{
    public const ENQUIRY_TYPES = [
        'general' => 'General',
        'prayer' => 'Prayer',
        'volunteering' => 'Volunteering',
        'events' => 'Events',
        'bible-school' => 'Bible School',
        'other' => 'Other',
    ];

    #[Locked]
    public string $title = '';

    #[Locked]
    public string $page = '';

    /** @var array<string, mixed> */
    #[Locked]
    public array $church = [];

    public ?string $openFaq = null;

    public string $name = '';

    public string $email = '';

    public string $enquiryType = 'general';

    public string $subject = '';

    public string $message = '';

    public ?string $notice = null;

    public ?string $error = null;

    public function mount(string $title, string $page): void
    {
        $this->title = $title;
        $this->page = $page;
        $this->church = ChurchData::get();

        if ($member = MemberSession::member()) {
            $this->name = $member['name'];
            $this->email = $member['email'];
        }
    }

    public function toggleFaq(int $index): void
    {
        $key = (string) $index;

        $this->openFaq = $this->openFaq === $key ? null : $key;
    }

    public function sendMessage(): void
    {
        $this->error = null;

        $this->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:200'],
            'enquiryType' => ['required', 'in:'.implode(',', array_keys(self::ENQUIRY_TYPES))],
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ]);

        $result = ContentApi::send('POST', 'contact', [
            'name' => $this->name,
            'email' => $this->email,
            'enquiry_type' => $this->enquiryType,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        if (! $result['ok']) {
            $this->error = $result['message'];

            return;
        }

        $this->notice = 'Thank you! Your message has been received and we will get back to you shortly.';
        $this->reset('subject', 'message');
    }

    public function openDirections()
    {
        return ChurchData::open($this->church['directions_url'] ?? null);
    }

    public function share(): void
    {
        if (! function_exists('nativephp_call')) {
            return;
        }

        Share::url($this->church['name'], $this->church['share']['message'] ?? '', $this->shareUrl());
    }

    public function shareUrl(): string
    {
        $share = $this->church['share'] ?? [];
        $store = function_exists('nativephp_call') && System::isAndroid() ? ($share['android_url'] ?? null) : ($share['ios_url'] ?? null);

        return $store ?: ($share['ios_url'] ?? $share['android_url'] ?? rtrim((string) config('services.citylife.url'), '/') ?: route('home'));
    }

    public function render()
    {
        return view('livewire.church-info');
    }
}
