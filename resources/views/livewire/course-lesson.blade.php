<div class="feature-page">
    <header class="feature-header">
        <a href="{{ route('course.show', $slug) }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">{{ $lesson ? 'LESSON '.$lesson['position'].' OF '.$lesson['total'] : 'LESSON' }}</span>
            <h1>{{ $lesson['title'] ?? 'Lesson' }}</h1>
        </div>
    </header>

    @if ($lockedUntil)
        <section class="feature-section">
            <p class="course-callout">{{ $error }} It opens on {{ date('l j F', strtotime($lockedUntil)) }}.</p>
        </section>
    @else
        <section class="feature-section">
            @if ($notice)<p class="prayer-notice-banner" role="status">{{ $notice }}</p>@endif
            @if ($error)<p class="prayer-error" role="alert">{{ $error }}</p>@endif

            <article class="card course-content">{!! $lesson['content'] !!}</article>

            @if ($quizResult)
                <p class="course-callout" role="status">You scored {{ $quizResult['score'] }}% ({{ $quizResult['correct'] }} of {{ $quizResult['total'] }}).</p>
            @elseif ($lesson['quiz_score'] !== null)
                <p class="course-callout">Your quiz score: {{ $lesson['quiz_score'] }}%</p>
            @endif

            @if ($lesson['quiz'] && $quizOpen)
                <form class="card prayer-card course-quiz" wire:submit="submitQuiz">
                    <h2>Quiz</h2>
                    @foreach ($lesson['quiz'] as $index => $question)
                        <fieldset wire:key="question-{{ $index }}">
                            <legend>{{ $index + 1 }}. {{ $question['question'] }}</legend>
                            @if ($question['type'] === 'text')
                                <input type="text" wire:model="answers.{{ $index }}" autocapitalize="off" placeholder="Your answer">
                            @else
                                @foreach ($question['options'] as $optionIndex => $option)
                                    <label class="prayer-check">
                                        <input type="radio" name="question-{{ $index }}" value="{{ $optionIndex }}" wire:model="answers.{{ $index }}">
                                        <span>{{ $option }}</span>
                                    </label>
                                @endforeach
                            @endif
                        </fieldset>
                    @endforeach
                    <button type="submit" class="prayer-submit" wire:loading.attr="disabled">Submit answers</button>
                </form>
            @elseif ($lesson['quiz'])
                <button type="button" class="feature-light-button course-action" wire:click="openQuiz">{{ $lesson['quiz_score'] !== null ? 'Retake quiz' : 'Take the quiz' }}</button>
            @endif

            <form class="card prayer-card course-notes" wire:submit="saveNotes">
                <h2>My notes</h2>
                <textarea wire:model="notes" rows="4" placeholder="Write your thoughts on this lesson…"></textarea>
                @error('notes')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror
                <button type="submit" class="feature-light-button course-action">Save notes</button>
            </form>

            @if (! $lesson['completed'])
                <button type="button" class="feature-primary" wire:click="markRead" wire:loading.attr="disabled">
                    Mark as read{{ $next && ! $next['locked'] ? ' and continue' : '' }}
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
                </button>
            @else
                <p class="feature-footnote">✓ You've completed this lesson.</p>
            @endif

            <nav class="course-nav" aria-label="Lesson navigation">
                @if ($previous && ! $previous['locked'])
                    <a href="{{ route('course.lesson', [$slug, $previous['slug']]) }}" wire:navigate>← {{ $previous['title'] }}</a>
                @endif
                @if ($next && ! $next['locked'])
                    <a href="{{ route('course.lesson', [$slug, $next['slug']]) }}" wire:navigate>{{ $next['title'] }} →</a>
                @elseif ($next)
                    <span>Next opens {{ date('l j F', strtotime($next['unlocks_on'])) }}</span>
                @endif
            </nav>
        </section>
    @endif
</div>
