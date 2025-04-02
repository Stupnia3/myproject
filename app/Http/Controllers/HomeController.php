<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        Log::info('HomeController::index called');
        $reviews = Review::with('user')->get()->map(function ($review) {
            return [
                'author' => $review->user ? $review->user->name : 'Неизвестный автор',
                'text' => $review->text,
                'rating' => $review->rating,
                'image' => $review->image,
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
