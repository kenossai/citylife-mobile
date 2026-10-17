<div class="feature-page">
    <header class="feature-header">
        <a href="{{ route('home') }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">CITY LIFE</span>
            <h1>Courses</h1>
        </div>
    </header>

    <div class="prayer-tabs course-tabs" role="tablist">
        @if ($signedIn)
            <button type="button" role="tab" aria-selected="{{ $tab === 'mine' ? 'true' : 'false' }}" @class(['active' => $tab === 'mine']) wire:click="showTab('mine')">My Courses</button>
        @endif
        <button type="button" role="tab" aria-selected="{{ $tab === 'explore' ? 'true' : 'false' }}" @class(['active' => $tab === 'explore']) wire:click="showTab('explore')">Explore</button>
        @if ($signedIn)
            <button type="button" role="tab" aria-selected="{{ $tab === 'certificates' ? 'true' : 'false' }}" @class(['active' => $tab === 'certificates']) wire:click="showTab('certificates')">Certificates</button>
        @endif
    </div>

    <section class="feature-section">
        @if (! $available)
            <p class="feature-footnote">Courses can't be loaded right now. Please check your connection and try again.</p>
        @elseif ($tab === 'mine')
            <div class="feature-list">
                @forelse ($mine as $enrollment)
                    <a href="{{ route('course.show', $enrollment['course']['slug']) }}" wire:navigate class="feature-row course-row" wire:key="mine-{{ $enrollment['id'] }}">
                        <div class="feature-row-copy">
                            <h3>{{ $enrollment['course']['title'] }}</h3>
                            @if ($enrollment['status'] === 'pending')
                                <span class="course-pill">Awaiting approval</span>
                            @else
                                <div class="feature-progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $enrollment['progress']['percent'] }}"><span style="width: {{ $enrollment['progress']['percent'] }}%"></span></div>
                                <small>{{ $enrollment['status'] === 'completed' ? 'Completed' : $enrollment['progress']['done'].' of '.$enrollment['progress']['total'].' lessons' }}</small>
                            @endif
                        </div>
                        <svg class="course-chev" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                    </a>
                @empty
                    <p class="feature-footnote">You haven't enrolled in a course yet. Browse the Explore tab to get started.</p>
                @endforelse
            </div>
        @elseif ($tab === 'certificates')
            <div class="feature-list">
                @forelse ($certificates as $certificate)
                    <article class="feature-row course-row" wire:key="certificate-{{ $certificate['enrollment_id'] }}">
                        <div class="feature-row-copy">
                            <h3>{{ $certificate['course_title'] }}</h3>
                            <p>{{ $certificate['grade'] ? $certificate['grade'].' · ' : '' }}Completed {{ date('j F Y', strtotime($certificate['issued_at'])) }}</p>
                            @if ($certificate['instructor'])<small>{{ $certificate['instructor'] }}</small>@endif
                        </div>
                    </article>
                @empty
                    <p class="feature-footnote">Complete a course to earn your certificate.</p>
                @endforelse
            </div>
        @else
            <div class="feature-list">
                @forelse ($catalogue as $course)
                    <a href="{{ route('course.show', $course['slug']) }}" wire:navigate class="feature-row course-row" wire:key="course-{{ $course['slug'] }}">
                        <div class="feature-row-copy">
                            @if ($course['category'])<span class="course-pill">{{ $course['category'] }}</span>@endif
                            <h3>{{ $course['title'] }}</h3>
                            @if ($course['description'])<p>{{ \Illuminate\Support\Str::limit(strip_tags($course['description']), 110) }}</p>@endif
                            <small>
                                {{ $course['lesson_count'] ?? 0 }} {{ \Illuminate\Support\Str::plural('lesson', $course['lesson_count'] ?? 0) }}
                                @if ($course['instructor']) · {{ $course['instructor'] }}@endif
                                · {{ $course['registration_open'] ? 'Registration open' : 'Registration closed' }}
                            </small>
                        </div>
                        <svg class="course-chev" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                    </a>
                @empty
                    <p class="feature-footnote">No courses are available right now.</p>
                @endforelse
            </div>
        @endif

        @unless ($signedIn)
            <p class="feature-footnote"><a href="{{ route('login') }}" wire:navigate>Sign in</a> to see your courses and progress.</p>
        @endunless
    </section>
</div>
