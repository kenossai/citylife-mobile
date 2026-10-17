<?php

namespace App\Livewire;

use App\Support\MemberSession;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class CourseLesson extends Component
{
    public string $slug = '';

    public string $lessonSlug = '';

    /** @var array<string, mixed> */
    public array $lesson = [];

    /** @var array<string, mixed>|null */
    public ?array $previous = null;

    /** @var array<string, mixed>|null */
    public ?array $next = null;

    public ?string $lockedUntil = null;

    public string $notes = '';

    public bool $quizOpen = false;

    /** @var array<int, mixed> */
    public array $answers = [];

    /** @var array{score: int, correct: int, total: int}|null */
    public ?array $quizResult = null;

    public ?string $notice = null;

    public ?string $error = null;

    public function mount(string $slug, string $lessonSlug)
    {
        if (! MemberSession::check()) {
            return $this->redirectRoute('login', navigate: true);
        }

        $this->slug = $slug;
        $this->lessonSlug = $lessonSlug;

        $result = MemberSession::request('GET', "me/courses/{$slug}/lessons/{$lessonSlug}");

        if ($result['status'] === 401) {
            return $this->redirectRoute('login', navigate: true);
        }

        if ($result['status'] === 403) {
            $this->lockedUntil = $result['body']['unlocks_on'] ?? null;
            $this->error = $result['message'];

            return null;
        }

        abort_unless($result['ok'], 404);

        $this->lesson = $result['data']['lesson'];
        $this->previous = $result['data']['previous'];
        $this->next = $result['data']['next'];
        $this->notes = $this->lesson['notes'] ?? '';

        return null;
    }

    public function saveNotes(): void
    {
        $this->validate(['notes' => ['nullable', 'string', 'max:5000']]);

        $result = MemberSession::request('POST', "me/courses/{$this->slug}/lessons/{$this->lessonSlug}/notes", ['notes' => $this->notes]);

        $this->notice = $result['ok'] ? 'Notes saved.' : null;
        $this->error = $result['ok'] ? null : $result['message'];
    }

    public function openQuiz(): void
    {
        $this->quizOpen = true;
        $this->quizResult = null;
        $this->answers = [];
    }

    public function submitQuiz(): void
    {
        $result = MemberSession::request('POST', "me/courses/{$this->slug}/lessons/{$this->lessonSlug}/quiz", ['answers' => (object) $this->answers]);

        if (! $result['ok']) {
            $this->error = $result['message'];

            return;
        }

        $this->error = null;
        $this->quizResult = $result['data'];
        $this->quizOpen = false;
        $this->lesson['quiz_score'] = $result['data']['score'];
    }

    public function markRead()
    {
        $result = MemberSession::request('POST', "me/courses/{$this->slug}/lessons/{$this->lessonSlug}/read");

        if (! $result['ok']) {
            $this->error = $result['message'];

            return null;
        }

        $this->lesson['completed'] = true;

        if ($result['data']['course_completed']) {
            return $this->redirectRoute('course.show', ['slug' => $this->slug], navigate: true);
        }

        if ($this->next && ! $this->next['locked']) {
            return $this->redirectRoute('course.lesson', ['slug' => $this->slug, 'lessonSlug' => $this->next['slug']], navigate: true);
        }

        $this->notice = $this->next
            ? 'Lesson complete. The next lesson opens on '.date('l j F', strtotime($this->next['unlocks_on'])).'.'
            : 'Lesson complete.';

        return null;
    }

    public function render()
    {
        return view('livewire.course-lesson');
    }
}
