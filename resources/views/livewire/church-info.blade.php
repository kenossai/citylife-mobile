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

    @if ($page === 'location')
        <section class="feature-hero location-hero">
            <span class="feature-eyebrow">COME AND JOIN US</span>
            <h2>You're welcome here.</h2>
            <p>Join us for worship and community in the heart of Sheffield.</p>
        </section>
        <section class="feature-section">
            <article class="feature-contact-card">
                <span class="feature-icon">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-7-6.5-7-12a7 7 0 0 1 14 0c0 5.5-7 12-7 12z"/><circle cx="12" cy="9" r="2.5"/></svg>
                </span>
                <span class="feature-kicker">FIND US</span>
                <h2>{{ $church['name'] }}</h2>
                <p>{{ $church['address'] }}@if ($church['landmark'])<br>{{ $church['landmark'] }}@endif</p>
                <button type="button" class="feature-primary" wire:click="openDirections">
                    Get directions
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 3h7v7m-1-6L10 14M19 13v7H4V5h7"/></svg>
                </button>
            </article>

            @foreach ($church['locations'] ?? [] as $location)
                @continue($loop->first)
                <article class="feature-contact-card" wire:key="location-{{ $loop->index }}">
                    <span class="feature-kicker">ALSO MEETING AT</span>
                    <h2>{{ $location['name'] }}</h2>
                    <p>{{ $location['address'] }}@if ($location['times'])<br>{{ $location['times'] }}@endif</p>
                </article>
            @endforeach

            <article class="feature-contact-card service-card">
                <span class="feature-kicker">GATHER WITH US</span>
                <h2>Weekly gatherings</h2>
                <div class="service-times">
                    @foreach ($church['service_times'] as $service)
                        @foreach ($service['times'] as $time)
                            <div class="service-time-row"><b>{{ $service['day'] }}</b><span>{{ $time }}</span></div>
                        @endforeach
                    @endforeach
                </div>
            </article>
        </section>
    @elseif ($page === 'call-us')
        <section class="feature-contact-card contact-page-card">
            <span class="invite-art" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg></span>
            <span class="feature-kicker">WE'RE HERE FOR YOU</span>
            <h2>Let's talk.</h2>
            <p>Have a question or need a hand? Call the church office and speak with our team.</p>
            <a class="feature-primary" href="tel:{{ preg_replace('/[^\d+]/', '', $church['phone']) }}">
                Call {{ $church['phone'] }}
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2z"/></svg>
            </a>
            <a class="feature-secondary" href="{{ route('email-us') }}" wire:navigate>Send a message instead</a>
        </section>
    @elseif ($page === 'email-us')
        <section class="feature-intro">
            <span class="feature-kicker">WE'RE HERE FOR YOU</span>
            <h2>Send us a message.</h2>
            <p>We'd love to hear from you. Your message goes straight to the City Life team.</p>
        </section>

        @if ($notice)<p class="prayer-notice-banner course-banner" role="status">{{ $notice }}</p>@endif

        <form class="card prayer-card contact-form" wire:submit="sendMessage">
            <label for="contact-name">Your name</label>
            <input id="contact-name" type="text" wire:model="name" autocomplete="name">
            @error('name')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror

            <label for="contact-email">Email</label>
            <input id="contact-email" type="email" wire:model="email" autocomplete="email" inputmode="email" autocapitalize="off">
            @error('email')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror

            <label for="contact-type">What is this about?</label>
            <select id="contact-type" wire:model="enquiryType">
                @foreach (\App\Livewire\ChurchInfo::ENQUIRY_TYPES as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>

            <label for="contact-subject">Subject</label>
            <input id="contact-subject" type="text" wire:model="subject">
            @error('subject')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror

            <label for="contact-message">Message</label>
            <textarea id="contact-message" wire:model="message" rows="5"></textarea>
            @error('message')<p class="prayer-error" role="alert">{{ $message }}</p>@enderror

            @if ($error)<p class="prayer-error" role="alert">{{ $error }}</p>@endif

            <button type="submit" class="prayer-submit" wire:loading.attr="disabled">Send message</button>
        </form>
    @elseif ($page === 'help-support')
        <section class="feature-intro">
            <span class="feature-kicker">WE'RE HAPPY TO HELP</span>
            <h2>How can we help?</h2>
            <p>Quick answers to help you feel at home.</p>
        </section>
        <section class="feature-section faq-section">
            <div class="feature-section-heading"><div><span class="feature-kicker">COMMON QUESTIONS</span><h2>FAQs</h2></div></div>
            <div class="feature-list faq-list">
                @foreach ($church['faqs'] as $index => $faq)
                    <article class="faq-item" wire:key="faq-{{ $index }}">
                        <button type="button" aria-expanded="{{ $openFaq === (string) $index ? 'true' : 'false' }}" wire:click="toggleFaq({{ $index }})">
                            <span>{{ $faq['question'] }}</span>
                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </button>
                        @if ($openFaq === (string) $index)
                            <p>{{ $faq['answer'] }}</p>
                        @endif
                    </article>
                @endforeach
            </div>
        </section>
        <a href="{{ route('email-us') }}" wire:navigate class="feature-contact-link">
            <span class="feature-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18v12H3zM3 7l9 7 9-7"/></svg></span>
            <span><b>Still need help?</b><small>Get in touch with our team.</small></span>
            <svg class="feature-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
        </a>
    @elseif ($page === 'share-app')
        <section class="invite-card" x-data="{ copied: false, error: '', async invite() { this.error = ''; const url = $el.dataset.shareUrl; try { if (navigator.share) { await navigator.share({ title: @js($church['name']), text: @js($church['share']['message'] ?? ''), url }); } else if (navigator.clipboard?.writeText) { await navigator.clipboard.writeText(url); this.copied = true; window.setTimeout(() => this.copied = false, 2500); } else { this.error = 'Sharing is unavailable in this browser.'; } } catch (error) { if (error.name !== 'AbortError') { this.error = 'Could not share the link. Please try again.'; } } } }" data-share-url="{{ $this->shareUrl() }}">
            <span class="invite-art" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M18 8a3 3 0 1 0-2.8-4M6 15a3 3 0 1 0 0-6m12 13a3 3 0 1 0 0-6M8.7 13.5l6.6 3.8m0-10.6-6.6 3.8"/><circle cx="18" cy="5" r="2"/><circle cx="6" cy="12" r="2"/><circle cx="18" cy="19" r="2"/></svg>
            </span>
            <span class="feature-kicker">PASS IT ON</span>
            <h2>Good things are better shared.</h2>
            <p>Invite someone to discover City Life and find a community in Sheffield.</p>
            @if ($native)
                <button type="button" class="feature-primary" wire:click="share">
                    Share City Life
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 16V4m-5 5 5-5 5 5M5 14v5h14v-5"/></svg>
                </button>
            @else
                <button type="button" class="feature-primary" @click="invite()">
                    <span x-text="copied ? 'Link copied' : 'Share City Life'">Share City Life</span>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 16V4m-5 5 5-5 5 5M5 14v5h14v-5"/></svg>
                </button>
                <p class="feature-feedback" role="status" x-show="copied" x-cloak>Invite link copied to clipboard.</p>
                <p class="feature-error" role="alert" x-show="error" x-text="error" x-cloak></p>
            @endif
        </section>
    @endif
</div>
