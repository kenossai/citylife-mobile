<?php

namespace App\Livewire;

use App\Support\ContentApi;
use App\Support\MemberSession;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class CourseDetail extends Component
{
    public string $slug = '';

    /** @var array<string, mixed> */
    public array $course = [];

    /** @var array<string, mixed>|null */
    public ?array $enrollment = null;

    /** @var array<int, array<string, mixed>> */
    public array $lessons = [];

    public bool $signedIn = false;

    public string $name = '';

    public string $email = '';

    public int $rating = 0;

    public string $review = '';

    public ?string $notice = null;

    public ?string $error = null;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->course = ContentApi::get("courses/{$slug}") ?? abort(404);
        $this->signedIn = MemberSession::check();
        $this->loadEnrollment();
    }

    private function loadEnrollment(): void
    {
        if (! $this->signedIn) {
            return;
        }

        $result = MemberSession::request('GET', "me/courses/{$this->slug}");

        if ($result['status'] === 401) {
            $this->signedIn = false;

            return;
        }

        $this->enrollment = $result['data']['enrollment'] ?? null;
        $this->lessons = $result['data']['lessons'] ?? [];
    }

    public function enrol(): void
    {
        $this->error = null;

        if (! $this->signedIn) {
            $this->validate([
                'name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'email', 'max:150'],
            ]);
        }

        $result = MemberSession::request('POST', "courses/{$this->slug}/enrol", $this->signedIn ? [] : ['name' => $this->name, 'email' => $this->email]);

        if (! $result['ok']) {
            $this->error = $result['message'];

            return;
        }

        $this->notice = 'Your enrolment request has been submitted. We will review it and be in touch shortly.';
        $this->reset('name', 'email');
        $this->loadEnrollment();
    }

    public function submitReview(): void
    {
        $this->error = null;

        $this->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:2000'],
        ]);

        $result = MemberSession::request('POST', "me/courses/{$this->slug}/review", ['rating' => $this->rating, 'body' => $this->review]);

        if (! $result['ok']) {
            $this->error = $result['message'];

            return;
        }

        $this->notice = 'Thank you for your review!';
        $this->loadEnrollment();
    }

    public function render()
    {
        return view('livewire.course-detail');
    }
}
