<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\SavedPropertyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');

    // Properties
    Route::get('/properties', [PropertyController::class, 'index'])->name('properties');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');


    // Saved Properties
    // (import moved to top)
    Route::get('/saved', fn() => Inertia::render('Saved'))->name('saved');
    Route::post('/saved', [SavedPropertyController::class, 'store'])->name('saved.store');
    Route::delete('/saved/{property}', [SavedPropertyController::class, 'destroy'])->name('saved.destroy');

    // Map
    Route::get('/map', fn() => Inertia::render('Map'))->name('map');
    Route::get('/map-data', [PropertyController::class, 'map'])->name('map.data');

   // Messaging routes
    Route::get('/messages', [MessagesController::class, 'index'])->name('messages');

    Route::get('/messages/{id}', [MessagesController::class, 'show'])->name('messages.show'); // optional detail view
    Route::get('/messages/property/{property_id}', [MessagesController::class, 'conversation'])->name('messages.conversation');

    Route::post('/messages', [MessagesController::class, 'store'])->name('messages.store'); // send message
    Route::post('/start-conversation', [MessagesController::class, 'start'])->name('messages.start'); // initial message

    // Notifications
    Route::get('/notifications', fn() => Inertia::render('Notifications'))->name('notifications');

    // Upload
    Route::get('/upload', fn() => Inertia::render('Upload'))->name('upload');

    // Documentation
    Route::get('/documentation', fn() => Inertia::render('Documentation'))->name('documentation');

    // Video
    Route::get('/video', fn() => Inertia::render('Video'))->name('video');
});