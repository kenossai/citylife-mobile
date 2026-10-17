<div class="feature-page">
    <header class="feature-header">
        <a href="{{ route('home') }}" wire:navigate data-app-back class="feature-back" aria-label="Back">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
        </a>
        <div>
            <span class="feature-kicker">CITY LIFE</span>
            <h1>{{ $title }}</h1>
        </div>
    </header>

    @if ($page === 'course')
        @php($courseProgress = (int) round(count($completedLessons) / count($lessons) * 100))
        <section class="feature-hero course-hero">
            <span class="feature-eyebrow">GROW IN FAITH</span>
            <h2>Bible School International</h2>
            <p>Explore the foundations of faith at your own pace.</p>
            <div class="feature-progress" role="progressbar" aria-label="Course progress" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $courseProgress }}">
                <span style="width: {{ $courseProgress }}%"></span>
            </div>
            <div class="feature-progress-meta">
                <span>{{ count($completedLessons) }} of {{ count($lessons) }} lessons complete</span>
                <span>{{ $courseProgress }}%</span>
            </div>
        </section>

        <section class="feature-section">
            <div class="feature-section-heading">
                <div><span class="feature-kicker">YOUR JOURNEY</span><h2>Course lessons</h2></div>
                <span class="feature-count">{{ count($lessons) }} lessons</span>
            </div>
            <div class="feature-list">
                @foreach ($lessons as $lesson)
                    @php($lessonComplete = in_array($lesson['id'], $completedLessons, true))
                    <article class="feature-row lesson-row" wire:key="lesson-{{ $lesson['id'] }}">
                        <span @class(['feature-icon', 'is-complete' => $lessonComplete])>
                            @if ($lessonComplete)
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                            @endif
                        </span>
                        <div class="feature-row-copy">
                            <h3>{{ $lesson['title'] }}</h3>
                            <p>{{ $lesson['description'] }}</p>
                            <small>{{ $lesson['duration'] }}</small>
                        </div>
                        <button type="button" class="feature-check" aria-label="{{ $lessonComplete ? 'Mark lesson incomplete' : 'Mark lesson complete' }}" @disabled(! $courseStarted) wire:click="toggleLesson({{ $lesson['id'] }})">
                            {{ $lessonComplete ? 'Done' : 'Mark done' }}
                        </button>
                    </article>
                @endforeach
            </div>
            <button type="button" class="feature-primary" wire:click="startCourse">
                {{ $courseStarted ? 'Continue learning' : 'Start course' }}
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 12h14m-6-6 6 6-6 6"/></svg>
            </button>
            <p class="feature-footnote">Your lesson progress is saved for this app session.</p>
        </section>
    @elseif ($page === 'notifications')
        @php($unreadCount = count(array_filter($notifications, fn ($notification) => ! $notification['read'])))
        <section class="feature-intro">
            <span class="feature-kicker">YOUR INBOX</span>
            <h2>{{ $unreadCount ? $unreadCount.' new updates' : 'You’re all caught up' }}</h2>
            <p>Church news and reminders, all in one place.</p>
        </section>
        @if ($unreadCount)
            <button type="button" class="feature-text-button" wire:click="markAllRead">Mark all as read</button>
        @endif
        <div class="feature-list notification-list">
            @foreach ($notifications as $notification)
                <article class="feature-row notification-row" wire:key="notification-{{ $notification['id'] }}">
                    <span class="feature-icon">
                        @if ($notification['icon'] === 'calendar')
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3v3m10-3v3M4 9h16M5 5h14a1 1 0 0 1 1 1v13H4V6a1 1 0 0 1 1-1z"/></svg>
                        @elseif ($notification['icon'] === 'megaphone')
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11v2a1 1 0 0 0 1 1h3l8 4V6L7 10H4a1 1 0 0 0-1 1zM19 9a4 4 0 0 1 0 6"/></svg>
                        @else
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11a4.8 4.8 0 0 1 8.8-2.6 4.8 4.8 0 0 1 8.8 2.6z"/></svg>
                        @endif
                    </span>
                    <div class="feature-row-copy">
                        <div class="notification-title">
                            <h2>{{ $notification['title'] }}</h2>
                            @unless ($notification['read'])<span class="notification-dot" aria-label="Unread"></span>@endunless
                        </div>
                        <p>{{ $notification['message'] }}</p>
                        @if ($notification['link'] ?? null)<a href="{{ $notification['link'] }}" class="feature-text-button" rel="noopener">Learn more</a>@endif
                        <small>{{ $notification['time'] }}</small>
                    </div>
                    @unless ($notification['read'])
                        <button type="button" class="feature-icon-button" aria-label="Mark {{ $notification['title'] }} as read" wire:click="markRead('{{ $notification['id'] }}')">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m5 12 4 4L19 6"/></svg>
                        </button>
                    @endunless
                </article>
            @endforeach
        </div>
    @elseif ($page === 'bible-reading-plan')
        @php($readingProgress = (int) round(count($completedReadings) / count($readings) * 100))
        <section class="feature-hero reading-hero">
            <span class="feature-eyebrow">7-DAY PLAN</span>
            <h2>Start your day in the Word</h2>
            <p>A week of short readings to help you build a daily rhythm.</p>
            <div class="feature-progress" role="progressbar" aria-label="Reading plan progress" aria-valuemin="0" aria-valuemax="100" aria-valuenow="{{ $readingProgress }}">
                <span style="width: {{ $readingProgress }}%"></span>
            </div>
            <div class="feature-progress-meta">
                <span>{{ count($completedReadings) }} of {{ count($readings) }} days complete</span>
                <span>{{ $readingProgress }}%</span>
            </div>
        </section>
        <section class="feature-section">
            <div class="feature-section-heading">
                <div><span class="feature-kicker">THIS WEEK</span><h2>Your readings</h2></div>
                <span class="feature-count">7 days</span>
            </div>
            <div class="feature-list">
                @foreach ($readings as $reading)
                    @php($readingComplete = in_array($reading['day'], $completedReadings, true))
                    <article class="feature-row reading-row" wire:key="reading-{{ $reading['day'] }}">
                        <span @class(['reading-day', 'is-complete' => $readingComplete])>{{ $readingComplete ? '✓' : str_pad((string) $reading['day'], 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="feature-row-copy">
                            <small>DAY {{ $reading['day'] }}</small>
                            <h3>{{ $reading['title'] }}</h3>
                            <p>{{ $reading['reference'] }}</p>
                        </div>
                        <button type="button" class="feature-check" aria-pressed="{{ $readingComplete ? 'true' : 'false' }}" wire:click="toggleReading({{ $reading['day'] }})">
                            {{ $readingComplete ? 'Completed' : 'Done' }}
                        </button>
                    </article>
                @endforeach
            </div>
            <p class="feature-footnote">Your reading progress is saved for this app session.</p>
        </section>
    @elseif ($page === 'small-groups')
        <section class="feature-hero groups-hero">
            <span class="feature-eyebrow">LIFE IS BETTER TOGETHER</span>
            <h2>Find your people</h2>
            <p>Connect, grow in faith and make room for real community.</p>
            <a class="feature-light-button" href="https://citylifecc.com/contact" target="_blank" rel="noopener">Ask about groups</a>
        </section>
        <section class="feature-section">
            <div class="feature-section-heading">
                <div><span class="feature-kicker">GET CONNECTED</span><h2>Explore a group</h2></div>
            </div>
            <div class="feature-list">
                @foreach ($groups as $group)
                    @php($groupInterested = in_array($group['id'], $interestedGroups, true))
                    <article class="feature-row group-row" wire:key="group-{{ $group['id'] }}">
                        <span class="feature-icon">
                            @if ($group['icon'] === 'book')
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 6c-2-1.5-5-2-9-2v14c4 0 7 .5 9 2 2-1.5 5-2 9-2V4c-4 0-7 .5-9 2zm0 0v14"/></svg>
                            @elseif ($group['icon'] === 'people')
                                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="3"/><path d="M3 20c0-3 2-5 6-5s6 2 6 5m1-9a3 3 0 1 0 0-6m1 10c3 .3 5 2 5 5"/></svg>
                            @else
                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.8 8.8c0 5.2-8.8 11-8.8 11s-8.8-5.8-8.8-11a4.8 4.8 0 0 1 8.8-2.6 4.8 4.8 0 0 1 8.8 2.6z"/></svg>
                            @endif
                        </span>
                        <div class="feature-row-copy">
                            <h3>{{ $group['name'] }}</h3>
                            <p>{{ $group['description'] }}</p>
                        </div>
                        <button type="button" @class(['feature-check', 'is-selected' => $groupInterested]) wire:click="toggleGroup('{{ $group['id'] }}')">
                            {{ $groupInterested ? 'Interested ✓' : 'I’m interested' }}
                        </button>
                    </article>
                @endforeach
            </div>
            <p class="feature-footnote">Your interest is noted for this app session. Contact the church to arrange a group.</p>
        </section>
    @elseif ($page === 'share-app')
        <section class="invite-card" x-data="{ copied: false, error: '', async invite() { this.error = ''; const url = $el.dataset.shareUrl; try { if (navigator.share) { await navigator.share({ title: 'City Life Church', text: 'Join me at City Life Church in Sheffield.', url }); } else if (navigator.clipboard?.writeText) { await navigator.clipboard.writeText(url); this.copied = true; window.setTimeout(() => this.copied = false, 2500); } else { this.error = 'Sharing is unavailable in this browser.'; } } catch (error) { if (error.name !== 'AbortError') { this.error = 'Could not share the link. Please try again.'; } } } }" data-share-url="{{ route('home') }}">
            <span class="invite-art" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M18 8a3 3 0 1 0-2.8-4M6 15a3 3 0 1 0 0-6m12 13a3 3 0 1 0 0-6M8.7 13.5l6.6 3.8m0-10.6-6.6 3.8"/><circle cx="18" cy="5" r="2"/><circle cx="6" cy="12" r="2"/><circle cx="18" cy="19" r="2"/></svg>
            </span>
            <span class="feature-kicker">PASS IT ON</span>
            <h2>Good things are better shared.</h2>
            <p>Invite someone to discover City Life and find a community in Sheffield.</p>
            <button type="button" class="feature-primary" @click="invite()">
                <span x-text="copied ? 'Link copied' : 'Share City Life'">Share City Life</span>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 16V4m-5 5 5-5 5 5M5 14v5h14v-5"/></svg>
            </button>
            <p class="feature-feedback" role="status" x-show="copied" x-cloak>Invite link copied to clipboard.</p>
            <p class="feature-error" role="alert" x-show="error" x-text="error" x-cloak></p>
        </section>
    @elseif ($page === 'location')
        <section class="feature-hero location-hero">
            <span class="feature-eyebrow">COME AND JOIN US</span>
            <h2>You’re welcome here.</h2>
            <p>Join us for worship and community in the heart of Sheffield.</p>
        </section>
        <section class="feature-section">
            <article class="feature-contact-card">
                <span class="feature-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-6.5-7-12a7 7 0 0 1 14 0c0 5.5-7 12-7 12z"/><circle cx="12" cy="9" r="2.5"/></svg>
                </span>
                <span class="feature-kicker">FIND US</span>
                <h2>City Life Church</h2>
                <p>1 South Parade<br>Shalesmoor, Sheffield<br>S3 8SS</p>
                <a class="feature-primary" href="https://maps.google.com/?q=1+South+Parade+Shalesmoor+Sheffield+S3+8SS" target="_blank" rel="noopener">
                    Get directions
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 3h7v7m-1-6L10 14M19 13v7H4V5h7"/></svg>
                </a>
            </article>
            <article class="feature-contact-card service-card">
                <span class="feature-kicker">GATHER WITH US</span>
                <h2>Weekly gatherings</h2>
                <div class="service-times">
                    <div class="service-time-row"><b>Sunday</b><span>Adult Bible Study <strong>9:00 am</strong></span></div>
                    <div class="service-time-row"><b>Sunday</b><span>Main Service <strong>10:30 am – 12:00 pm</strong></span></div>
                    <div class="service-time-row"><b>Thursday</b><span>Prayer Meeting <strong>7:30 pm</strong></span></div>
                </div>
            </article>
        </section>
    @elseif ($page === 'call-us' || $page === 'email-us')
        <section class="feature-contact-card contact-page-card">
            <span class="invite-art" aria-hidden="true">
                @if ($page === 'call-us')
                    <svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>
                @else
                    <svg viewBox="0 0 24 24"><path d="M3 6h18v12H3zM3 7l9 7 9-7"/></svg>
                @endif
            </span>
            <span class="feature-kicker">WE’RE HERE FOR YOU</span>
            @if ($page === 'call-us')
                <h2>Let’s talk.</h2>
                <p>Have a question or need a hand? Call the church office and speak with our team.</p>
            @else
                <h2>Send us a message.</h2>
                <p>We’d love to hear from you. Send an email to the City Life team.</p>
            @endif
            <a class="feature-primary" href="{{ $page === 'call-us' ? 'tel:01142728243' : 'mailto:admin1@citylifecc.com' }}">
                {{ $page === 'call-us' ? 'Call 0114 272 8243' : 'Email admin1@citylifecc.com' }}
                @if ($page === 'call-us')
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>
                @else
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18v12H3zM3 7l9 7 9-7"/></svg>
                @endif
            </a>
            <a class="feature-secondary" href="https://citylifecc.com/contact" target="_blank" rel="noopener">More ways to contact us</a>
        </section>
    @elseif ($page === 'app-settings')
        @php($settingLabels = [
            'push_notifications' => ['Push notifications', 'Receive important church updates.'],
            'event_reminders' => ['Event reminders', 'Get a reminder before upcoming events.'],
            'email_updates' => ['Email updates', 'Hear about news and what’s happening.'],
        ])
        <section class="feature-intro">
            <span class="feature-kicker">MAKE IT YOURS</span>
            <h2>Your preferences</h2>
            <p>Choose how City Life keeps you in the loop.</p>
        </section>
        <section class="feature-list settings-list" aria-label="Notification preferences">
            @foreach ($settingLabels as $key => [$label, $description])
                <div class="setting-row" wire:key="setting-{{ $key }}">
                    <div><h2>{{ $label }}</h2><p>{{ $description }}</p></div>
                    <button type="button" role="switch" aria-label="{{ $label }}" aria-checked="{{ $settings[$key] ? 'true' : 'false' }}" @class(['feature-switch', 'is-on' => $settings[$key]]) wire:click="toggleSetting('{{ $key }}')">
                        <span></span>
                    </button>
                </div>
            @endforeach
        </section>
        <p class="feature-footnote">Preferences are saved for this app session.</p>
        <a href="{{ route('help-support') }}" wire:navigate class="feature-link-row">
            <span><b>Need help?</b><small>Get answers or contact the church.</small></span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
        </a>
    @elseif ($page === 'help-support')
        <section class="feature-intro">
            <span class="feature-kicker">WE’RE HAPPY TO HELP</span>
            <h2>How can we help?</h2>
            <p>Quick answers to help you feel at home.</p>
        </section>
        <section class="feature-section faq-section">
            <div class="feature-section-heading"><div><span class="feature-kicker">COMMON QUESTIONS</span><h2>FAQs</h2></div></div>
            <div class="feature-list faq-list">
                @foreach ($faqs as $faq)
                    <article class="faq-item" wire:key="faq-{{ $faq['id'] }}">
                        <button type="button" aria-expanded="{{ $openFaq === $faq['id'] ? 'true' : 'false' }}" wire:click="toggleFaq('{{ $faq['id'] }}')">
                            <span>{{ $faq['question'] }}</span>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        @if ($openFaq === $faq['id'])
                            <p>{{ $faq['answer'] }}
                                @if ($faq['id'] === 'location')
                                    <a href="{{ route('location') }}" wire:navigate>See directions</a>
                                @elseif ($faq['id'] === 'online')
                                    <a href="https://citylifecc.com/media" target="_blank" rel="noopener">Explore messages</a>
                                @endif
                            </p>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
        <a href="https://citylifecc.com/contact" target="_blank" rel="noopener" class="feature-contact-link">
            <span class="feature-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18v12H3zM3 7l9 7 9-7"/></svg></span>
            <span><b>Still need help?</b><small>Get in touch with our team.</small></span>
            <svg class="feature-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
        </a>
    @endif
</div>
