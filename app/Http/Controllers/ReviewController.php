<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function create()
    {
        return Inertia::render('AddReview', [
            'auth' => [
                'user' => auth()->user(),
            ],
            'csrf_token' => csrf_token(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
        ]);

        Review::create([
            'user_id' => auth()->id(),
            'text' => $request->text,
            'rating' => $request->rating,
            'image' => auth()->user()->profile_photo_path ?? '/storage/images/avatardefault.png',
        ]);

        // Исправляем имя маршрута на 'reviews.create'
        return redirect()->route('reviews.create')->with('message', 'Отзыв успешно добавлен!');
    }
}
