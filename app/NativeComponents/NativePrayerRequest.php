<?php

namespace App\NativeComponents;

use App\Support\ContentApi;
use App\Support\MemberSession;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;

class NativePrayerRequest extends NativeComponent
{
    /** @var array<int, string> */
    public const CATEGORIES = ['Personal', 'Family', 'Health', 'Spiritual', 'Work', 'Church', 'Praise Report'];

    public string $name = '';

    public string $category = 'Personal';

    public string $request = '';

    public bool $isPrivate = false;

    public bool $sending = false;

    public ?string $error = null;

    public bool $sent = false;

    public function mount(): void
    {
        $this->name = MemberSession::member()['first_name'] ?? '';
    }

    public function send(?string $submitted = null): void
    {
        $text = trim($submitted ?? $this->request);

        $this->error = null;

        if (mb_strlen($text) < 10) {
            $this->error = 'Please write at least 10 characters.';

            return;
        }

        if (mb_strlen($text) > 1000) {
            $this->error = 'Please keep it under 1,000 characters.';

            return;
        }

        $this->sending = true;

        $result = MemberSession::request('POST', 'prayer-requests', [
            'name' => trim($this->name),
            'category' => in_array($this->category, self::CATEGORIES, true) ? $this->category : 'Personal',
            'request' => $text,
            'is_private' => $this->isPrivate,
        ]);

        $this->sending = false;

        if (! $result['ok']) {
            $this->error = $result['message'];

            return;
        }

        ContentApi::forget('prayer-requests');
        $this->sent = true;
        $this->request = '';
    }

    public function again(): void
    {
        $this->sent = false;
    }

    public function goHome(): void
    {
        $this->exitToWeb('/prayer-requests');
    }

    public function render(): View
    {
        return view('native.native-prayer-request', ['categories' => array_combine(self::CATEGORIES, self::CATEGORIES)]);
    }
}
