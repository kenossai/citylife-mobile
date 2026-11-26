<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * What this phone remembers about the person's sermons: the ones they played recently and the ones they saved.
 * It lives on the device (there is no account for it), so it is kept in the app's own cache store.
 */
class LibraryStore
{
    private const RECENT_KEY = 'citylife-library:recent';

    private const FAVOURITES_KEY = 'citylife-library:favourites';

    private const RECENT_LIMIT = 20;

    /**
     * @return array<int, int>
     */
    public static function recent(): array
    {
        return self::ids(self::RECENT_KEY);
    }

    /**
     * @return array<int, int>
     */
    public static function favourites(): array
    {
        return self::ids(self::FAVOURITES_KEY);
    }

    public static function played(int $sermonId): void
    {
        $recent = array_values(array_unique([$sermonId, ...self::recent()]));

        Cache::forever(self::RECENT_KEY, array_slice($recent, 0, self::RECENT_LIMIT));
    }

    public static function isFavourite(int $sermonId): bool
    {
        return in_array($sermonId, self::favourites(), true);
    }

    /**
     * Save or un-save a sermon. Returns whether it is saved afterwards.
     */
    public static function toggleFavourite(int $sermonId): bool
    {
        $favourites = self::favourites();

        if (in_array($sermonId, $favourites, true)) {
            Cache::forever(self::FAVOURITES_KEY, array_values(array_diff($favourites, [$sermonId])));

            return false;
        }

        Cache::forever(self::FAVOURITES_KEY, [$sermonId, ...$favourites]);

        return true;
    }

    /**
     * @return array<int, int>
     */
    private static function ids(string $key): array
    {
        return array_values(array_map('intval', (array) Cache::get($key, [])));
    }
}
