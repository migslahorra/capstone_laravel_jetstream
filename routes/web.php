<?php

use App\Http\Controllers\PropertyController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// This is the main route for the application that renders the welcome page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Group all verified and authenticated routes together
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // This is the route for dashboard that are for verified and authenticated users only
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard'); 

    // This route is for properties that are for verified and authenticated users only
    Route::get('/properties', [PropertyController::class, 'index'])->name('properties');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');

    // This is for the saved properties route that is for verified and authenticated users only
    Route::get('/saved', function () {
        return Inertia::render('Saved');
    })->name('saved');

    // This route is for the map that are for verified and authenticated users only
    Route::get('/map', function () {
        return Inertia::render('Map');
    })->name('map');

    // This route is for the messages that are for verified and authenticated users only
    Route::get('/messages', function () {
        return Inertia::render('Messages');
    })->name('messages');

    // This route is for the notifications that are for verified and authenticated users only
    Route::get('/notifications', function () {
        return Inertia::render('Notifications');
    })->name('notifications');

    // This route is for the notifications that are for verified and authenticated users only
    Route::get('/upload', function () {
        return Inertia::render('Upload');
    })->name('upload');

    // This route is for the documentation of the application for verified and authenticated users only 
    // to know how to use the application
    Route::get('/documentation', function () {
        return Inertia::render('Documentation');
    })->name('documentation');

    // This route is for the video presentation of the application for verified and authenticated users only
    // to know how to use the application showing its features and functionalities
    Route::get('/video', function () {
        return Inertia::render('Video');
    })->name('video');
});
