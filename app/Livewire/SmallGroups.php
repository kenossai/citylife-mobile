<?php

namespace App\Livewire;

use App\Support\ContentApi;
use App\Support\MemberSession;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class SmallGroups extends Component
{
    /** @var array<int, array<string, mixed>> */
    public array $groups = [];

    public bool $available = true;

    public ?string $openGroup = null;

    public string $name = '';

    public string $email = '';

    public string $phone = '';

    public string $message = '';

    /** @var array<int, string> */
    public array $sent = [];

    public ?string $error = null;

    public function mount(): void
    {
        $groups = ContentApi::get('ministries');

        $this->available = $groups !== null;
        $this->groups = $groups ?? [];

        if ($member = MemberSession::member()) {
            $this->name = $member['name'];
            $this->email = $member['email'];
            $this->phone = (string) ($member['phone'] ?? '');
        }
    }

    public function open(string $slug): void
    {
        $this->openGroup = $this->openGroup === $slug ? null : $slug;
        $this->error = null;
        $this->resetValidation();
    }

    public function connect(string $slug): void
    {
        abort_unless(in_array($slug, array_column($this->groups, 'slug'), true), 404);

        $this->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $result = ContentApi::send('POST', "ministries/{$slug}/connect", [
            'full_name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone ?: null,
            'message' => $this->message ?: null,
        ]);

        if (! $result['ok']) {
            $this->error = $result['message'];

            return;
        }

        $this->sent[] = $slug;
        $this->openGroup = null;
        $this->reset('message');
    }

    public function render()
    {
        return view('livewire.small-groups');
    }
}
