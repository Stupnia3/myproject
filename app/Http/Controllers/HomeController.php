<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        Log::info('HomeController::index called');

        $reviews = Review::with('user')
            ->inRandomOrder()
            ->limit(5) // Ограничиваем до 5 случайных отзывов
            ->get()
            ->map(function ($review) {
                return [
                    'author' => $review->user ? $review->user->name : 'Неизвестный автор',
                    'text' => $review->text,
                    'rating' => $review->rating,
                    'image' => $review->user && $review->user->photo
                        ? "/storage/{$review->user->photo}"
                        : '/storage/images/avatardefault.png',
                ];
            });

        Log::info('Reviews sent to frontend:', $reviews->toArray());

        // Устанавливаем title в зависимости от маршрута
        $title = request()->route()->named('about') ? 'О нас' : 'Главная';

        return Inertia::render('MainPage', [
            'title' => $title,
            'reviews' => $reviews->values()->all(),
        ]);
    }
}
