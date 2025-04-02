<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Models\Event;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::controller(AuthController::class)->group(function () {
    // Публичные маршруты
    Route::get('/about', [HomeController::class, 'index'])->name('about'); // Используем HomeController
    Route::get('/events', 'showEvents')->name('events');

    // Авторизация (доступны всем)
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

    // Мероприятия (доступны всем)
    Route::post('/event/{id}/register', 'registerForEvent')->name('event.register');
});

// Главная страница с использованием HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');

// Маршруты для отзывов
Route::get('/add-review', [ReviewController::class, 'create'])->middleware('auth')->name('reviews.create');
Route::post('/reviews', [ReviewController::class, 'store'])->middleware('auth')->name('reviews.store');

// Авторизованные маршруты
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');
    // Только для админов
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/', [AuthController::class, 'showAdminPanel'])->name('admin');
        Route::post('/events', [AuthController::class, 'storeEvent'])->name('admin.events.store');
    });
});
