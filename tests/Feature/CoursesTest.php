<?php

namespace Tests\Feature;

use App\Livewire\CourseDetail;
use App\Livewire\CourseLesson;
use App\Livewire\Courses;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class CoursesTest extends TestCase
{
    private const MEMBER = [
        'id' => 1, 'first_name' => 'Ada', 'last_name' => 'Obi', 'name' => 'Ada Obi', 'email' => 'ada@example.com',
        'phone' => null, 'membership_number' => 'CL1', 'membership_status' => 'visitor', 'member_since' => null,
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.citylife.url' => 'https://site.test']);
    }

    private function signIn(): void
    {
        session()->put(['citylife.member_token' => 'tok', 'citylife.member' => self::MEMBER]);
    }

    /** @return array<string, mixed> */
    private function course(array $overrides = []): array
    {
        return [
            'slug' => 'cdc', 'title' => 'Christian Development Course', 'category' => 'Foundations', 'description' => 'Build a firm foundation.',
            'image_url' => null, 'instructor' => 'Pastor Sam', 'start_date' => null, 'end_date' => null, 'lesson_count' => 2,
            'has_certificate' => true, 'is_membership_course' => true, 'registration_open' => true,
            'lessons' => [
                ['slug' => 'one', 'number' => 1, 'title' => 'Salvation', 'group' => null, 'description' => 'New birth'],
                ['slug' => 'two', 'number' => 2, 'title' => 'The Word', 'group' => null, 'description' => null],
            ],
            ...$overrides,
        ];
    }

    /** @return array<string, mixed> */
    private function enrollment(string $status = 'active', array $progress = ['done' => 1, 'total' => 2, 'percent' => 50], array $overrides = []): array
    {
        return ['id' => 4, 'status' => $status, 'certificate_issued' => false, 'progress' => $progress, 'has_review' => false, 'course' => $this->course(), ...$overrides];
    }

    /** @return array<string, mixed> */
    private function lessonState(string $slug, int $number, array $overrides = []): array
    {
        return ['slug' => $slug, 'number' => $number, 'title' => "Lesson {$number}", 'group' => null, 'description' => null, 'completed' => false, 'quiz_score' => null, 'has_quiz' => false, 'locked' => false, 'unlocks_on' => null, ...$overrides];
    }

    public function test_guests_browse_the_catalogue_and_are_pointed_to_sign_in(): void
    {
        Http::fake(['site.test/api/v1/courses' => Http::response(['data' => [$this->course()]])]);

        Livewire::test(Courses::class)
            ->assertSee('Christian Development Course')
            ->assertSee('Registration open')
            ->assertSee('2 lessons')
            ->assertSee('Sign in')
            ->assertDontSee('My Courses');
    }

    public function test_the_list_says_so_when_the_website_cannot_be_reached(): void
    {
        Http::fake(['site.test/*' => Http::response([], 500)]);

        Livewire::test(Courses::class)->assertSee('be loaded right now');
    }

    public function test_members_see_their_courses_with_progress_first(): void
    {
        $this->signIn();
        Http::fake([
            'site.test/api/v1/me/courses' => Http::response(['data' => [$this->enrollment()]]),
            'site.test/api/v1/courses' => Http::response(['data' => [$this->course()]]),
        ]);

        Livewire::test(Courses::class)
            ->assertSet('tab', 'mine')
            ->assertSee('1 of 2 lessons')
            ->call('showTab', 'explore')
            ->assertSee('Foundations');
    }

    public function test_certificates_load_when_the_tab_is_opened(): void
    {
        $this->signIn();
        Http::fake([
            'site.test/api/v1/me/certificates' => Http::response(['data' => [['enrollment_id' => 4, 'course_title' => 'Christian Development Course', 'grade' => 'Merit', 'issued_at' => '2026-09-01T10:00:00+01:00', 'instructor' => 'Pastor Sam']]]),
            'site.test/api/v1/me/courses' => Http::response(['data' => []]),
            'site.test/api/v1/courses' => Http::response(['data' => []]),
        ]);

        Livewire::test(Courses::class)->call('showTab', 'certificates')->assertSee('Merit')->assertSee('1 September 2026');
    }

    public function test_a_guest_enrols_with_name_and_email(): void
    {
        Http::fake([
            'site.test/api/v1/courses/cdc' => Http::response(['data' => $this->course()]),
            'site.test/api/v1/courses/cdc/enrol' => Http::response(['data' => ['status' => 'pending']], 201),
        ]);

        Livewire::test(CourseDetail::class, ['slug' => 'cdc'])
            ->assertSee('Enrol in this course')
            ->call('enrol')
            ->assertHasErrors(['name', 'email'])
            ->set('name', 'Sam Taylor')
            ->set('email', 'sam@example.com')
            ->call('enrol')
            ->assertHasNoErrors()
            ->assertSee('enrolment request has been submitted');

        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/courses/cdc/enrol') && $request['email'] === 'sam@example.com' && ! $request->hasHeader('Authorization'));
    }

    public function test_enrolment_errors_from_the_website_are_shown(): void
    {
        Http::fake([
            'site.test/api/v1/courses/cdc' => Http::response(['data' => $this->course()]),
            'site.test/api/v1/courses/cdc/enrol' => Http::response(['message' => 'Enrolment is only available from within the church premises.'], 403),
        ]);

        Livewire::test(CourseDetail::class, ['slug' => 'cdc'])
            ->set('name', 'Sam Taylor')
            ->set('email', 'sam@example.com')
            ->call('enrol')
            ->assertSee('only available from within the church premises');
    }

    public function test_closed_registration_hides_the_form(): void
    {
        Http::fake(['site.test/api/v1/courses/cdc' => Http::response(['data' => $this->course(['registration_open' => false])])]);

        Livewire::test(CourseDetail::class, ['slug' => 'cdc'])->assertSee("Registration for this course isn't open", false)->assertDontSee('Enrol in this course');
    }

    public function test_an_unknown_course_is_not_found(): void
    {
        Http::fake(['site.test/*' => Http::response([], 404)]);

        $this->get(route('course.show', 'nope'))->assertNotFound();
    }

    public function test_a_pending_enrolment_shows_the_waiting_message_and_no_lessons_to_open(): void
    {
        $this->signIn();
        Http::fake([
            'site.test/api/v1/courses/cdc' => Http::response(['data' => $this->course()]),
            'site.test/api/v1/me/courses/cdc' => Http::response(['data' => ['enrollment' => $this->enrollment('pending'), 'lessons' => []]]),
        ]);

        Livewire::test(CourseDetail::class, ['slug' => 'cdc'])->assertSee('awaiting approval')->assertDontSee('Enrol in this course');
    }

    public function test_active_enrolment_lists_lessons_with_locks_and_scores(): void
    {
        $this->signIn();
        Http::fake([
            'site.test/api/v1/courses/cdc' => Http::response(['data' => $this->course()]),
            'site.test/api/v1/me/courses/cdc' => Http::response(['data' => [
                'enrollment' => $this->enrollment(),
                'lessons' => [
                    $this->lessonState('one', 1, ['completed' => true, 'quiz_score' => 80]),
                    $this->lessonState('two', 2, ['locked' => true, 'unlocks_on' => '2026-11-08']),
                ],
            ]]),
        ]);

        $this->get(route('course.show', 'cdc'))
            ->assertOk()
            ->assertSee('Quiz score 80%')
            ->assertSee('Opens Sunday 8 November')
            ->assertSee(route('course.lesson', ['cdc', 'one']), false)
            ->assertDontSee(route('course.lesson', ['cdc', 'two']), false);
    }

    public function test_a_completed_member_course_offers_a_review(): void
    {
        $this->signIn();
        Http::fake([
            'site.test/api/v1/courses/cdc' => Http::response(['data' => $this->course()]),
            'site.test/api/v1/me/courses/cdc' => Http::sequence()
                ->push(['data' => ['enrollment' => $this->enrollment('completed', ['done' => 2, 'total' => 2, 'percent' => 100], ['certificate_issued' => true]), 'lessons' => []]])
                ->push(['data' => ['enrollment' => $this->enrollment('completed', ['done' => 2, 'total' => 2, 'percent' => 100], ['has_review' => true]), 'lessons' => []]]),
            'site.test/api/v1/me/courses/cdc/review' => Http::response(['data' => null], 201),
        ]);

        Livewire::test(CourseDetail::class, ['slug' => 'cdc'])
            ->assertSee('earned your certificate')
            ->assertSee('Welcome to City Life membership')
            ->call('submitReview')
            ->assertHasErrors(['rating'])
            ->set('rating', 5)
            ->set('review', 'Life changing')
            ->call('submitReview')
            ->assertSee('Thank you for your review')
            ->assertDontSee('How was the course?');

        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/review') && $request['rating'] === 5 && $request->hasHeader('Authorization', 'Bearer tok'));
    }

    public function test_a_lesson_shows_its_content_and_saves_notes(): void
    {
        $this->signIn();
        Http::fake([
            'site.test/api/v1/me/courses/cdc/lessons/one' => Http::response(['data' => [
                'lesson' => [...$this->lessonState('one', 1, ['has_quiz' => true]), 'content' => '<p>Born again</p>', 'notes' => 'Earlier note', 'quiz' => [['type' => 'multiple_choice', 'question' => 'Pick B', 'options' => ['A', 'B']]], 'position' => 1, 'total' => 2],
                'previous' => null,
                'next' => ['slug' => 'two', 'title' => 'The Word', 'locked' => false, 'unlocks_on' => null],
            ]]),
            'site.test/api/v1/me/courses/cdc/lessons/one/notes' => Http::response(['data' => null]),
        ]);

        Livewire::test(CourseLesson::class, ['slug' => 'cdc', 'lessonSlug' => 'one'])
            ->assertSee('Born again', false)
            ->assertSet('notes', 'Earlier note')
            ->set('notes', 'New note')
            ->call('saveNotes')
            ->assertSee('Notes saved');

        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/notes') && $request['notes'] === 'New note');
    }

    public function test_the_quiz_is_submitted_to_the_website_for_grading(): void
    {
        $this->signIn();
        Http::fake([
            'site.test/api/v1/me/courses/cdc/lessons/one' => Http::response(['data' => [
                'lesson' => [...$this->lessonState('one', 1, ['has_quiz' => true]), 'content' => '<p>x</p>', 'notes' => null, 'quiz' => [['type' => 'multiple_choice', 'question' => 'Pick B', 'options' => ['A', 'B']]], 'position' => 1, 'total' => 2],
                'previous' => null,
                'next' => null,
            ]]),
            'site.test/api/v1/me/courses/cdc/lessons/one/quiz' => Http::response(['data' => ['score' => 100, 'correct' => 1, 'total' => 1]]),
        ]);

        Livewire::test(CourseLesson::class, ['slug' => 'cdc', 'lessonSlug' => 'one'])
            ->assertDontSee('Pick B')
            ->call('openQuiz')
            ->assertSee('Pick B')
            ->set('answers.0', '1')
            ->call('submitQuiz')
            ->assertSee('You scored 100%')
            ->assertSet('quizOpen', false);

        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/quiz') && ((array) ($request->data()['answers'] ?? []))[0] === '1');
    }

    public function test_marking_a_lesson_read_moves_on_when_the_next_is_unlocked(): void
    {
        $this->signIn();
        Http::fake([
            'site.test/api/v1/me/courses/cdc/lessons/one' => Http::response(['data' => [
                'lesson' => [...$this->lessonState('one', 1), 'content' => '<p>x</p>', 'notes' => null, 'quiz' => [], 'position' => 1, 'total' => 2],
                'previous' => null,
                'next' => ['slug' => 'two', 'title' => 'The Word', 'locked' => false, 'unlocks_on' => null],
            ]]),
            'site.test/api/v1/me/courses/cdc/lessons/one/read' => Http::response(['data' => ['completed' => true, 'course_completed' => false, 'review_prompt' => false, 'progress' => ['done' => 1, 'total' => 2]]]),
        ]);

        Livewire::test(CourseLesson::class, ['slug' => 'cdc', 'lessonSlug' => 'one'])
            ->call('markRead')
            ->assertRedirect(route('course.lesson', ['cdc', 'two']));
    }

    public function test_marking_a_lesson_read_explains_when_the_next_is_still_locked(): void
    {
        $this->signIn();
        Http::fake([
            'site.test/api/v1/me/courses/cdc/lessons/one' => Http::response(['data' => [
                'lesson' => [...$this->lessonState('one', 1), 'content' => '<p>x</p>', 'notes' => null, 'quiz' => [], 'position' => 1, 'total' => 2],
                'previous' => null,
                'next' => ['slug' => 'two', 'title' => 'The Word', 'locked' => true, 'unlocks_on' => '2026-11-08'],
            ]]),
            'site.test/api/v1/me/courses/cdc/lessons/one/read' => Http::response(['data' => ['completed' => true, 'course_completed' => false, 'review_prompt' => false, 'progress' => ['done' => 1, 'total' => 2]]]),
        ]);

        Livewire::test(CourseLesson::class, ['slug' => 'cdc', 'lessonSlug' => 'one'])
            ->call('markRead')
            ->assertNoRedirect()
            ->assertSee('The next lesson opens on Sunday 8 November');
    }

    public function test_finishing_the_last_lesson_returns_to_the_course(): void
    {
        $this->signIn();
        Http::fake([
            'site.test/api/v1/me/courses/cdc/lessons/two' => Http::response(['data' => [
                'lesson' => [...$this->lessonState('two', 2), 'content' => '<p>x</p>', 'notes' => null, 'quiz' => [], 'position' => 2, 'total' => 2],
                'previous' => ['slug' => 'one', 'title' => 'Salvation', 'locked' => false, 'unlocks_on' => null],
                'next' => null,
            ]]),
            'site.test/api/v1/me/courses/cdc/lessons/two/read' => Http::response(['data' => ['completed' => true, 'course_completed' => true, 'review_prompt' => true, 'progress' => ['done' => 2, 'total' => 2]]]),
        ]);

        Livewire::test(CourseLesson::class, ['slug' => 'cdc', 'lessonSlug' => 'two'])
            ->call('markRead')
            ->assertRedirect(route('course.show', 'cdc'));
    }

    public function test_a_locked_lesson_says_when_it_opens(): void
    {
        $this->signIn();
        Http::fake(['site.test/api/v1/me/courses/cdc/lessons/two' => Http::response(['message' => 'This lesson is not yet available.', 'unlocks_on' => '2026-11-08'], 403)]);

        $this->get(route('course.lesson', ['cdc', 'two']))->assertOk()->assertSee('It opens on Sunday 8 November');
    }

    public function test_lessons_need_a_signed_in_member(): void
    {
        $this->get(route('course.lesson', ['cdc', 'one']))->assertRedirect(route('login'));
    }
}
