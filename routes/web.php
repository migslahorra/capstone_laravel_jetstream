<?php

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\MessagesController;
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

// Protected Routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn () => Inertia::render('Dashboard'))->name('dashboard');

    // Properties
    Route::get('/properties', [PropertyController::class, 'index'])->name('properties');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

    // Map
    Route::get('/map', fn () => Inertia::render('Map'))->name('map');
    Route::get('/map-data', [PropertyController::class, 'map'])->name('map.data');

    // Upload
    Route::get('/upload', fn () => Inertia::render('Upload'))->name('upload');

    // Saved Properties
    Route::get('/saved', [SavedPropertyController::class, 'index'])->name('saved'); // 👈 Fix: match "route('saved')" in Vue
    Route::post('/saved', [SavedPropertyController::class, 'store'])->name('saved.store');
    Route::delete('/saved/{propertyId}', [SavedPropertyController::class, 'destroy'])->name('saved.destroy');
    Route::get('/saved-ids', [SavedPropertyController::class, 'ids'])->name('saved.ids');

    // Optional: separate view for SavedProperties page
    Route::get('/saved-properties', fn () => Inertia::render('SavedProperties'))->name('saved.properties');

    // Messages
    Route::get('/messages', [MessagesController::class, 'index'])->name('messages');
    Route::get('/messages/{id}', [MessagesController::class, 'show'])->name('messages.show');
    Route::get('/messages/property/{property_id}', [MessagesController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages', [MessagesController::class, 'store'])->name('messages.store');
    Route::post('/start-conversation', [MessagesController::class, 'start'])->name('messages.start');

    // Notifications
    Route::get('/notifications', fn () => Inertia::render('Notifications'))->name('notifications');

    // Documentation
    Route::get('/documentation', fn () => Inertia::render('Documentation'))->name('documentation');

    // Video
    Route::get('/video', fn () => Inertia::render('Video'))->name('video');
});