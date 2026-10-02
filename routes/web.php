<?php

use App\Livewire\EventDetail;
use App\Livewire\Events;
use App\Livewire\Give;
use App\Livewire\Home;
use App\Livewire\Placeholder;
use App\Livewire\PrayerRequests;
use App\Livewire\Profile;
use App\Livewire\SeriesDetail;
use App\Livewire\SermonPlayer;
use App\Livewire\Sermons;
use App\Livewire\Splash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::get('/', Splash::class)->name('splash');
Route::get('/home', Home::class)->name('home');
Route::get('/profile', Profile::class)->name('profile');
Route::get('/prayer-requests', PrayerRequests::class)->name('prayer-requests');
Route::get('/sermons', Sermons::class)->name('sermons');
Route::get('/sermons/series/{slug}', SeriesDetail::class)->name('sermons.series');
Route::get('/sermons/series/{slug}/{number}', SermonPlayer::class)->whereNumber('number')->name('sermons.play');
Route::get('/give', Give::class)->name('give');
Route::get('/events', Events::class)->name('events');
Route::get('/events/{id}', EventDetail::class)->whereNumber('id')->name('events.show');

foreach ([
    'course', 'notifications', 'bible-reading-plan', 'small-groups',
    'share-app', 'location', 'call-us', 'email-us', 'app-settings', 'help-support',
] as $page) {
    Route::get("/{$page}", Placeholder::class)->defaults('title', Str::headline($page))->name($page);
}
