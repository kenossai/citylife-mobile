<?php

use App\NativeComponents\NativeEventDetail;
use App\NativeComponents\NativeEvents;
use App\NativeComponents\NativePrayerRequest;
use App\NativeComponents\NativeSermonPlayer;
use Illuminate\Support\Facades\Route;

/*
| Native (SwiftUI / Jetpack Compose) screens. They live under /native so they never collide with the web-view screens, which
| remain the rest of the app. A native screen returns to the web app with exitToWeb().
*/
Route::native('/native/events', NativeEvents::class);
Route::native('/native/events/{id}', NativeEventDetail::class);
Route::native('/native/prayer', NativePrayerRequest::class);
Route::native('/native/sermon/{id}', NativeSermonPlayer::class);
