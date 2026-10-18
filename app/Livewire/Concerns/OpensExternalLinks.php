<?php

namespace App\Livewire\Concerns;

trait OpensExternalLinks
{
    /**
     * Ask the page to open a web link in the in-app browser. The page's script makes the native call from inside the webview.
     */
    protected function openExternal(?string $url): void
    {
        if (blank($url) || ! preg_match('~^https?://~i', $url)) {
            return;
        }

        $this->dispatch('open-external', url: $url);
    }
}
