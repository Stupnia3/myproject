<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Models\Event;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::controller(AuthController::class)->group(function () {
    // Публичные маршруты
    Route::get('/', fn() => Inertia::render('MainPage', ['title' => 'Главная']))->name('home');
    Route::get('/about', fn() => Inertia::render('About', ['title' => 'О нас']))->name('about');
    Route::get('/events', 'showEvents')->name('events');

    // Авторизация
    Route::get('/login', 'showAuthForm')->name('login')->defaults('type', 'login');
    Route::post('/login', 'login');
    Route::get('/register', 'showAuthForm')->name('register')->defaults('type', 'register');
    Route::post('/register', 'register');
    Route::get('/event/{id}/register-form', function ($id) {
        $events = Event::all();
        return Inertia::render('EventRegisterForm', [
            'events' => $events,
            'eventId' => (int)$id,
        ]);
    })->name('event.register.form');
    // Мероприятия
    Route::post('/event/{id}/register', 'registerForEvent')->name('event.register');
});

// Авторизованные маршруты
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/', [AuthController::class, 'showAdminPanel'])->name('admin');
        Route::post('/events', [AuthController::class, 'storeEvent'])->name('admin.events.store');
    });
});
