<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Models\Event;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('MainPage', ['title' => 'Главная страница']);
})->name('home');

Route::get('/about', function () {
    return Inertia::render('About', ['title' => 'О нас']);
})->name('about');

Route::get('/retreat', function () {
    return Inertia::render('Retreat', ['title' => 'Ретрит']);
})->name('retreat');

Route::get('/events', [AuthController::class, 'showEvents'])->name('events');

Route::get('/therapy', function () {
    return Inertia::render('Therapy', ['title' => 'Терапия']);
})->name('therapy');

Route::get('/contacts', function () {
    return Inertia::render('Contacts', ['title' => 'Контакты']);
})->name('contacts');

Route::get('/test', function () {
    return Inertia::render('Test', ['title' => 'Тест']);
})->name('test');

// Маршруты для мероприятий
Route::post('/event/{id}/register', [AuthController::class, 'registerForEvent'])->name('event.register');
Route::get('/event/{id}/register-form', function ($id) {
    $events = Event::all();
    return Inertia::render('EventRegisterForm', [
        'events' => $events,
        'eventId' => (int)$id,
    ]);
})->name('event.register.form');

// Маршруты авторизации
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Маршруты, доступные только аутентифицированным пользователям
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'showDashboard'])->name('dashboard');
    Route::get('/admin', [AuthController::class, 'showAdminPanel'])->name('admin')->middleware('admin');
    Route::post('/admin/events', [AuthController::class, 'storeEvent'])->name('admin.events.store')->middleware('admin');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Маршрут для выхода
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
