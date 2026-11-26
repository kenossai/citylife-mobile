<?php

namespace App\Livewire;

use App\Support\MemberSession;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class CertificateDetail extends Component
{
    /** @var array<string, mixed> */
    public array $certificate = [];

    public string $holder = '';

    public function mount(int $enrollmentId)
    {
        $member = MemberSession::member();

        if (! $member) {
            return $this->redirectRoute('login', navigate: true);
        }

        $result = MemberSession::request('GET', 'me/certificates');

        if ($result['status'] === 401) {
            return $this->redirectRoute('login', navigate: true);
        }

        $this->certificate = collect($result['data'] ?? [])->firstWhere('enrollment_id', $enrollmentId) ?? abort(404);
        $this->holder = $member['name'];

        return null;
    }

    public function render()
    {
        return view('livewire.certificate-detail');
    }
}
