<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/api/routes', function () {
    return collect(Route::getRoutes())
        ->map(fn ($r) => $r->getName())
        ->filter()
        ->values();
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
