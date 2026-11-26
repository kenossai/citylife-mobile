<?php

namespace App\NativeComponents;

use App\Support\ContentApi;
use App\Support\SermonMedia;
use App\Support\SermonViews;
use Illuminate\View\View;
use Native\Mobile\Edge\NativeComponent;
use Native\Mobile\Facades\Browser;

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
        }
    }

    public function close(): void
    {
        $this->exitToWeb($this->sermonId > 0 ? "/sermons/watch/{$this->sermonId}" : '/sermons');
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
        return view('native.native-sermon-player');
    }
}
