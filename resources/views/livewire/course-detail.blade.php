<div class="feature-page">
    <header class="feature-header">
        <a href="{{ route('course') }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">{{ $course['category'] ?: 'COURSE' }}</span>
            <h1>{{ $course['title'] }}</h1>
        </div>
    </header>

    @if ($notice)<p class="prayer-notice-banner course-banner" role="status">{{ $notice }}</p>@endif

    <section class="feature-hero course-hero">
        @if ($course['instructor'])<span class="feature-eyebrow">WITH {{ \Illuminate\Support\Str::upper($course['instructor']) }}</span>@endif
        @if ($course['description'])<p>{{ strip_tags($course['description']) }}</p>@endif

        @if ($enrollment && $enrollment['status'] !== 'pending')
            <div class="feature-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $enrollment['progress']['percent'] }}"><span style="width: {{ $enrollment['progress']['percent'] }}%"></span></div>
            <div class="feature-progress-meta">
                <span>{{ $enrollment['progress']['done'] }} of {{ $enrollment['progress']['total'] }} lessons complete</span>
                <span>{{ $enrollment['progress']['percent'] }}%</span>
            </div>
        @endif
    </section>

    <section class="feature-section">
        @if ($enrollment && $enrollment['status'] === 'pending')
            <p class="course-callout">Your enrolment is awaiting approval. We'll email you once you're in{{ $signedIn ? '' : ', with a link to set your password' }}.</p>
        @endif

        @if ($enrollment && $enrollment['status'] === 'completed')
            <p class="course-callout">
                You've completed this course{{ $enrollment['certificate_issued'] ? ' and earned your certificate' : '' }}.
                @if ($course['is_membership_course']) Welcome to City Life membership.@endif
            </p>

            @if (! ($enrollment['has_review'] ?? false))
                <form class="card prayer-card course-review" wire:submit="submitReview">
                    <h2>How was the course?</h2>
                    <div class="course-stars" role="radiogroup" aria-label="Rating">
                        @foreach (range(1, 5) as $star)
                            <button type="button" role="radio" aria-checked="{{ $rating === $star ? 'true' : 'false' }}" aria-label="{{ $star }} {{ \Illuminate\Support\Str::plural('star', $star) }}" @class(['on' => $rating >= $star]) wire:click="$set('rating', {{ $star }})">★</button>
                        @endforeach
                    </div>
                    @error('rating')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror
                    <textarea wire:model="review" rows="3" placeholder="Tell us what you thought (optional)"></textarea>
                    @if ($error)<p class="prayer-error" role="alert">{{ $error }}</p>@endif
                    <button type="submit" class="prayer-submit">Send review</button>
                </form>
            @endif
        @endif

        <div class="feature-section-heading">
            <div><span class="feature-kicker">{{ $enrollment && $enrollment['status'] !== 'pending' ? 'YOUR JOURNEY' : 'SYLLABUS' }}</span><h2>Lessons</h2></div>
            <span class="feature-count">{{ count($lessons) ?: count($course['lessons'] ?? []) }} lessons</span>
        </div>

        <div class="feature-list">
            @if ($lessons)
                @foreach ($lessons as $lesson)
                    @if ($lesson['group'] && ($loop->first || $lessons[$loop->index - 1]['group'] !== $lesson['group']))
                        <h3 class="course-group">{{ $lesson['group'] }}</h3>
                    @endif
                    <a @if (! $lesson['locked']) href="{{ route('course.lesson', [$slug, $lesson['slug']]) }}" wire:navigate @endif @class(['feature-row', 'lesson-row', 'is-locked' => $lesson['locked']]) wire:key="lesson-{{ $lesson['slug'] }}" @if ($lesson['locked']) aria-disabled="true" @endif>
                        <span @class(['feature-icon', 'is-complete' => $lesson['completed']])>
                            @if ($lesson['completed'])
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
                            @elseif ($lesson['locked'])
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 11h14v10H5zM8 11V8a4 4 0 0 1 8 0v3"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                            @endif
                        </span>
                        <div class="feature-row-copy">
                            <h3>{{ $lesson['number'] }}. {{ $lesson['title'] }}</h3>
                            @if ($lesson['description'])<p>{{ $lesson['description'] }}</p>@endif
                            <small>
                                @if ($lesson['locked']) Opens {{ date('l j F', strtotime($lesson['unlocks_on'])) }}
                                @elseif ($lesson['quiz_score'] !== null) Quiz score {{ $lesson['quiz_score'] }}%
                                @elseif ($lesson['completed']) Completed
                                @else Ready to read @endif
                            </small>
                        </div>
                    </a>
                @endforeach
            @else
                @foreach ($course['lessons'] ?? [] as $lesson)
                    <article class="feature-row lesson-row" wire:key="outline-{{ $lesson['slug'] }}">
                        <span class="feature-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg></span>
                        <div class="feature-row-copy">
                            <h3>{{ $lesson['number'] }}. {{ $lesson['title'] }}</h3>
                            @if ($lesson['description'])<p>{{ $lesson['description'] }}</p>@endif
                        </div>
                    </article>
                @endforeach
            @endif
        </div>

        @if (! $enrollment)
            @if (! $course['registration_open'])
                <p class="course-callout">Registration for this course isn't open right now.</p>
            @else
                <form class="card prayer-card course-enrol" wire:submit="enrol">
                    <h2>Enrol in this course</h2>
                    @unless ($signedIn)
                        <label for="enrol-name">Your name</label>
                        <input id="enrol-name" type="text" wire:model="name" autocomplete="name" placeholder="Full name">
                        @error('name')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror

                        <label for="enrol-email">Email</label>
                        <input id="enrol-email" type="email" wire:model="email" autocomplete="email" inputmode="email" autocapitalize="off" placeholder="you@example.com">
                        @error('email')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror

                        <small>We'll email you once your enrolment is approved, with a link to set your password. Already have an account? <a href="{{ route('login') }}" wire:navigate>Sign in</a> first.</small>
                    @else
                        <small>Your enrolment will be reviewed by our team. You'll get an email when it's approved.</small>
                    @endunless
                    @if ($error)<p class="prayer-error" role="alert">{{ $error }}</p>@endif
                    <button type="submit" class="prayer-submit" wire:loading.attr="disabled">Enrol</button>
                </form>
            @endif
        @endif
    </section>
</div>
