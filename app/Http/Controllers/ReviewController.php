<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        Log::info('ReviewController::store called', ['request' => $request->all()]);

        $request->validate([
            'event_id' => 'required|exists:events,id',
            'text' => 'required|string|max:1000',
            'rating' => 'required|integer|between:1,5',
        ]);

        $hasRegistration = EventRegistration::where('user_id', auth()->id())
            ->where('event_id', $request->event_id)
            ->where('status', 'confirmed')
            ->exists();

        if (!$hasRegistration) {
            Log::warning('User not registered or not confirmed for event', [
                'user_id' => auth()->id(),
                'event_id' => $request->event_id,
            ]);
            return Redirect::back()->withErrors(['message' => 'Вы можете оставить отзыв только для мероприятий, на которые зарегистрированы и подтверждены.']);
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'event_id' => $request->event_id,
            'text' => $request->text,
            'rating' => $review->rating,
        ]);

        Log::info('Review created', ['review_id' => $review->id]);

        return Redirect::back()->with('message', 'Отзыв успешно добавлен!');
    }

    public function index($eventId)
    {
        Log::info('ReviewController::index called', ['event_id' => $eventId]);

        $reviews = Review::with('user')
            ->where('event_id', $eventId)
            ->orderBy('created_at', 'desc')
            ->get();

        Log::info('Reviews fetched for event', [
            'event_id' => $eventId,
            'count' => $reviews->count(),
        ]);

        return response()->json($reviews);
    }
}
