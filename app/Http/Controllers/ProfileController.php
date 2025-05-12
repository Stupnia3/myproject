<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\EventRegistration;

class ProfileController extends Controller
{
    /**
     * Display the user's profile information.
     */
    public function show(Request $request): Response
    {
        $user = $request->user();
        $registrations = EventRegistration::with('event')
            ->where('user_id', $user->id)
            ->get();

        $calendarEvents = $registrations->map(function ($registration) {
            $event = $registration->event;
            $startDate = $event->start_date->toIso8601String();
            $endDate = $event->end_date
                ? $event->end_date->toIso8601String()
                : $event->start_date->toIso8601String();

            $eventData = [
                'title' => $event->title,
                'start' => $startDate,
                'end' => $endDate,
                'url' => route('event.details', $event->id),
                'id' => $event->id,
            ];

            Log::info('Calendar event', $eventData);

            return $eventData;
        })->toArray();

        return Inertia::render('Profile/Show', [
            'auth' => ['user' => $user],
            'registrations' => $registrations,
            'calendarEvents' => $calendarEvents,
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'user' => $request->user(),
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
            'phone' => ['required', 'string', 'regex:/^\+7 \(\d{3}\)-\d{3}-\d{2}-\d{2}$/'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        if ($request->hasFile('photo')) {
            // Удаляем старую аватарку, если она не дефолтная
            if ($user->photo && $user->photo !== 'images/avatardefault.png') {
                Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('images', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
            $user->save();
        }

        return Redirect::route('profile.show')->with('success', 'Профиль обновлён');
    }

    /**
     * Update the user's avatar.
     */
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'max:2048'],
        ]);

        $user = $request->user();

        if ($user->photo && $user->photo !== 'images/avatardefault.png') {
            Storage::disk('public')->delete($user->photo);
        }

        $photoPath = $request->file('photo')->store('images', 'public');
        $user->update(['photo' => $photoPath]);

        return Redirect::route('profile.show')->with('success', 'Аватар обновлён');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->photo && $user->photo !== 'images/avatardefault.png') {
            Storage::disk('public')->delete($user->photo);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
