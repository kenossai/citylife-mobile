<?php

namespace App\Livewire;

use App\Support\ContentApi;
use App\Support\MemberSession;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Courses extends Component
{
    public string $tab = 'explore';

    public bool $signedIn = false;

    public bool $available = true;

    /** @var array<int, array<string, mixed>> */
    public array $catalogue = [];

    /** @var array<int, array<string, mixed>> */
    public array $mine = [];

    /** @var array<int, array<string, mixed>> */
    public array $certificates = [];

    public function mount(): void
    {
        $catalogue = ContentApi::get('courses');

        $this->available = $catalogue !== null;
        $this->catalogue = $catalogue ?? [];
        $this->signedIn = MemberSession::check();

        if ($this->signedIn) {
            $result = MemberSession::request('GET', 'me/courses');

            if ($result['status'] === 401) {
                $this->signedIn = false;
            } else {
                $this->mine = $result['data'] ?? [];
                $this->tab = $this->mine ? 'mine' : 'explore';
            }
        }
    }

    public function showTab(string $tab): void
    {
        if (! in_array($tab, ['mine', 'explore', 'certificates'], true)) {
            return;
        }

        if ($tab === 'certificates' && ! $this->certificates && $this->signedIn) {
            $this->certificates = MemberSession::request('GET', 'me/certificates')['data'] ?? [];
        }

        $this->tab = $tab;
    }

    public function render()
    {
        return view('livewire.courses');
    }
}
