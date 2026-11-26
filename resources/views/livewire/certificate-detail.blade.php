<div class="feature-page">
    <header class="feature-header">
        <a href="{{ route('course') }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">CERTIFICATE</span>
            <h1>{{ $certificate['course_title'] }}</h1>
        </div>
    </header>

    <section class="feature-section">
        <article class="card certificate-card">
            <span class="feature-kicker">CITY LIFE INTERNATIONAL CHURCH</span>
            <p class="certificate-lead">This certifies that</p>
            <h2>{{ $holder }}</h2>
            <p class="certificate-lead">has successfully completed</p>
            <h3>{{ $certificate['course_title'] }}</h3>

            @if ($certificate['grade'])
                <p class="certificate-grade">{{ $certificate['grade'] }}@if ($certificate['average_score'] !== null) · {{ round($certificate['average_score']) }}%@endif</p>
            @endif

            <dl>
                <div><dt>Completed</dt><dd>{{ date('j F Y', strtotime($certificate['issued_at'])) }}</dd></div>
                @if ($certificate['duration_weeks'])<div><dt>Duration</dt><dd>{{ $certificate['duration_weeks'] }} {{ \Illuminate\Support\Str::plural('week', $certificate['duration_weeks']) }}</dd></div>@endif
                @if ($certificate['instructor'])<div><dt>Instructor</dt><dd>{{ $certificate['instructor'] }}</dd></div>@endif
            </dl>
        </article>
    </section>
</div>
