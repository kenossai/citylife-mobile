<?php

namespace App\Livewire\Concerns;

use App\Support\ContentApi;

/**
 * Shared by the Bible screens: the versions the church is licensed to show, and the reader's chosen one.
 */
trait PicksBibleVersion
{
    /** @var array<int, array{id: int, abbreviation: string, title: string, language: ?string, copyright: ?string}> */
    public array $versions = [];

    public int $versionId = 0;

    /** True when the website has no Bible set up or cannot reach it. */
    public bool $unavailable = false;

    protected function loadVersions(): void
    {
        $versions = ContentApi::isConfigured() ? ContentApi::get('bible/versions', 3600) : null;

        if (! $versions) {
            $this->unavailable = true;

            return;
        }

        $this->versions = $versions;

        $chosen = (int) session('citylife.bible_version', 0);
        $this->versionId = in_array($chosen, array_column($versions, 'id'), true) ? $chosen : $versions[0]['id'];
    }

    /** @return array<int, array{id: string, title: string, abbreviation: ?string, canon: ?string, chapters: int}> */
    protected function loadBooks(): array
    {
        return $this->unavailable ? [] : (ContentApi::get("bible/{$this->versionId}/books", 86400) ?? []);
    }

    protected function rememberVersion(int $id): bool
    {
        if (! in_array($id, array_column($this->versions, 'id'), true)) {
            return false;
        }

        $this->versionId = $id;
        session()->put('citylife.bible_version', $id);

        return true;
    }
}
