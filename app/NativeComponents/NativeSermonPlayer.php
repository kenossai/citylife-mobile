<?php

namespace App\NativeComponents;

use App\Support\ContentApi;
use App\Support\LibraryStore;
use App\Support\SermonMedia;
use App\Support\SermonViews;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;
use Native\Mobile\Facades\Browser;
use Native\Mobile\Facades\Share;

/**
 * Plays a sermon inside the app. The web-view screens cannot embed YouTube: the app shell sends every web address, even one loaded in a
 * frame, to the system browser. A native web view shows the video as a full page instead, so nothing leaves the app.
 */
class NativeSermonPlayer extends NativeComponent
{
    public int $sermonId = 0;

    /** @var array<string, mixed> */
    public array $sermon = [];

    /** @var array{type: string, src: string}|null */
    public ?array $media = null;

    public function mount(): void
    {
        $this->sermonId = (int) $this->param('id');
        $this->sermon = ContentApi::get("sermons/{$this->sermonId}") ?? [];

        if ($this->sermon !== []) {
            $this->media = SermonMedia::from($this->sermon['video_url'] ?? null);
            SermonViews::record($this->sermonId);
            LibraryStore::played($this->sermonId);
            $this->isFavourite = LibraryStore::isFavourite($this->sermonId);
        }
    }

    public bool $readMore = false;

    public bool $isFavourite = false;

    public function toggleFavourite(): void
    {
        $this->isFavourite = LibraryStore::toggleFavourite($this->sermonId);
    }

    public function toggleReadMore(): void
    {
        $this->readMore = ! $this->readMore;
    }

    public function share(): void
    {
        Share::url($this->sermon['title'] ?? 'Sermon', 'Watch this message from City Life Church', $this->sermon['video_url'] ?? '');
    }

    /**
     * Leaves the player by stepping the web view back to the page it was on, so the history is not left with an extra entry.
     */
    public function close(): void
    {
        $this->exitToWeb('/app/return');
    }

    public function onBackPressed(): void
    {
        $this->close();
    }

    public function openLink(): void
    {
        if (($this->media['type'] ?? null) === 'link') {
            Browser::inApp($this->media['src']);
        }
    }

    public function render(): View
    {
        $description = trim(strip_tags((string) ($this->sermon['description'] ?? '')));

        return view('native.native-sermon-player', [
            'description' => $description,
            'shortDescription' => $this->readMore ? $description : Str::limit($description, 220),
            'canReadMore' => mb_strlen($description) > 220,
            'playerSrc' => $this->media ? $this->media['src'].(str_contains($this->media['src'], '?') ? '&' : '?').'autoplay=1' : null,
        ]);
    }
}
