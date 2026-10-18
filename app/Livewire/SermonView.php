<?php

namespace App\Livewire;

use App\Support\ContentApi;
use App\Support\SermonMedia;
use App\Support\SermonViews;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class SermonView extends Component
{
    /** @var array<string, mixed> */
    public array $sermon = [];

    public function mount(int $id): void
    {
        $this->sermon = ContentApi::get("sermons/{$id}") ?? abort(404);

        $views = SermonViews::record($id);

        if ($views !== null) {
            $this->sermon['views'] = $views;
        }
    }

    public function render()
    {
        return view('livewire.sermon-view', [
            'media' => SermonMedia::from($this->sermon['video_url'] ?? null),
        ]);
    }
}
