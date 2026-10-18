<?php

namespace App\Support;

class SermonViews
{
    /**
     * Count a view once per device session. Returns the new total when the website counted it.
     */
    public static function record(?int $sermonId): ?int
    {
        if (! $sermonId || ! ContentApi::isConfigured()) {
            return null;
        }

        $viewed = session('citylife.viewed_sermons', []);

        if (in_array($sermonId, $viewed, true)) {
            return null;
        }

        $result = ContentApi::send('POST', "sermons/{$sermonId}/view");

        if (! $result['ok']) {
            return null;
        }

        session()->put('citylife.viewed_sermons', [...$viewed, $sermonId]);
        ContentApi::forget('sermons');
        ContentApi::forget("sermons/{$sermonId}");

        return $result['data']['views'] ?? null;
    }
}
