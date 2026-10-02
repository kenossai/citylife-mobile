<?php

use App\Livewire\EventDetail;
use App\Livewire\Events;
use App\Livewire\Home;
use App\Livewire\Placeholder;
use App\Livewire\Splash;
use Illuminate\Support\Facades\Route;

Route::get('/', Splash::class)->name('splash');
Route::get('/home', Home::class)->name('home');
Route::get('/events', Events::class)->name('events');
Route::get('/events/{id}', EventDetail::class)->whereNumber('id')->name('events.show');

foreach (['sermons', 'give', 'course'] as $page) {
    Route::get("/{$page}", Placeholder::class)->defaults('title', ucfirst($page))->name($page);
}
