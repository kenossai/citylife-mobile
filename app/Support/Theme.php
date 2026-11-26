<?php

namespace App\Support;

class Theme
{
    public const CHOICES = ['system', 'light', 'dark'];

    public const COOKIE = 'cl_resolved';

    public static function preference(): string
    {
        $stored = session('citylife.theme', 'system');

        return in_array($stored, self::CHOICES, true) ? $stored : 'system';
    }

    /**
     * "light" or "dark". When the reader follows the device the server cannot know which it is, so it uses what the page's script
     * last saw in a cookie. That way a new page is drawn in the right colours straight away.
     */
    public static function resolved(): string
    {
        $preference = self::preference();

        if ($preference !== 'system') {
            return $preference;
        }

        return request()->cookie(self::COOKIE) === 'dark' ? 'dark' : 'light';
    }

    public static function set(string $theme): bool
    {
        if (! in_array($theme, self::CHOICES, true)) {
            return false;
        }

        session()->put('citylife.theme', $theme);

        return true;
    }
}
