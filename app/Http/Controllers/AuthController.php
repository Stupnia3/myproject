<?php
namespace App\Http\Controllers;

use App\Models\{Event, EventRegistration, Teacher, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log, DB};
use Inertia\Inertia;

class AuthController extends Controller
{
    private const DASHBOARD_PATH = '/dashboard';
    private const EVENTS_PATH = '/events';
    private const HOME_PATH = '/';

    public function login(Request $request)
    {
        if (Auth::attempt($this->validateLogin($request))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Перенаправляем в зависимости от роли
            if ($user->role === 'admin') {
                return redirect(self::DASHBOARD_PATH);
            } else {
                return redirect(self::EVENTS_PATH);
            }
        }
        return back()->withErrors(['email' => 'Неверные учетные данные']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(self::HOME_PATH);
    }

    public function register(Request $request)
    {
        $data = $this->validateRegistration($request);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('images', 'public');
        } else {
            $data['photo'] = 'images/avatardefault.png';
        }

        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        Auth::login($user);

        // Перенаправляем в зависимости от роли
        if ($user->role === 'admin') {
            return redirect(self::DASHBOARD_PATH);
        } else {
            return redirect(self::EVENTS_PATH);
        }
    }

    private function validateRegistration(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^\+7 \(\d{3}\)-\d{3}-\d{2}-\d{2}$/'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    private function validateLogin(Request $request): array
    {
        return $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    }

    public function showAuthForm(string $type)
    {
        $component = $type === 'login' ? 'Login' : 'Register';
        return Inertia::render($component);
    }

    public function showDashboard(Request $request)
    {
        $selectedEventId = $request->input('event_id');
        $events = Event::query()->select(['id', 'title'])->get();
        $registrations = $selectedEventId
            ? EventRegistration::query()
                ->where('event_id', $selectedEventId)
                ->select(['id', 'name', 'email', 'phone', 'created_at', 'status'])
                ->get()
            : collect();

        if (config('app.debug')) {
            Log::info('Dashboard data', [
                'selectedEventId' => $selectedEventId,
                'registrations_count' => $registrations->count(),
            ]);
        }

        return Inertia::render('Dashboard', [
            'user' => fn() => Auth::user(),
            'events' => $events,
            'registrations' => $registrations,
            'selectedEventId' => $selectedEventId,
        ]);
    }

    public function showAdminPanel(Request $request)
    {
        $teachers = Teacher::select(['id', 'name'])->get();

        if (config('app.debug')) {
            Log::info('AdminPanel data', [
                'teachers' => $teachers->toArray(),
            ]);
        }

        return Inertia::render('AdminPanel', [
            'user' => fn() => Auth::user(),
            'teachers' => $teachers,
        ]);
    }

    public function updateRegistrationStatus(Request $request, $registrationId)
    {
        Log::info('Received request to update registration status', [
            'registration_id' => $registrationId,
            'request_data' => $request->all(),
        ]);

        $validated = $request->validate([
            'status' => ['required', 'in:confirmed,rejected'],
        ]);

        try {
            $registration = EventRegistration::findOrFail($registrationId);
            $updated = $registration->update(['status' => $validated['status']]);

            if ($updated) {
                Log::info('Registration status updated successfully', [
                    'registration_id' => $registrationId,
                    'status' => $validated['status'],
                ]);
            } else {
                Log::error('Failed to update registration status', [
                    'registration_id' => $registrationId,
                    'status' => $validated['status'],
                ]);
                return redirect()->back()->withErrors(['status' => 'Не удалось обновить статус записи']);
            }
        } catch (\Exception $e) {
            Log::error('Exception while updating registration status', [
                'registration_id' => $registrationId,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->withErrors(['status' => 'Произошла ошибка: ' . $e->getMessage()]);
        }

        return redirect()->back()->with('success', 'Статус записи обновлен');
    }

    public function deleteRegistration($registrationId)
    {
        $registration = EventRegistration::findOrFail($registrationId);
        $event = $registration->event;

        if ($event && $event->occupied_seats > 0) {
            $event->decrement('occupied_seats');
        }

        $registration->delete();

        Log::info('Registration deleted', [
            'registration_id' => $registrationId,
        ]);

        return redirect()->back()->with('success', 'Запись удалена');
    }

    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'practical_parts' => ['required', 'array', 'min:1'],
            'practical_parts.*' => ['required', 'string'],
            'methodologies' => ['required', 'array', 'min:1'],
            'methodologies.*' => ['required', 'string'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['in:art-therapy,master-class,retreat'],
            'teachers' => ['nullable', 'array'],
            'teachers.*' => ['exists:teachers,id'],
            'location' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'integer', 'min:0'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'total_seats' => ['required', 'integer', 'min:1'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('events', 'public');
        }

        $event = Event::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'practical_parts' => $validated['practical_parts'],
            'methodologies' => $validated['methodologies'],
            'tags' => $validated['tags'] ?? [],
            'location' => $validated['location'],
            'duration' => $validated['duration'],
            'photo' => $photoPath,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_seats' => $validated['total_seats'],
            'occupied_seats' => 0,
        ]);

        if (!empty($validated['teachers'])) {
            $event->teachers()->sync($validated['teachers']);
        }

        return redirect()->back()->with('success', 'Мероприятие успешно добавлено!');
    }

    public function showEvents()
    {
        $events = Event::all()->map(function ($event) {
            $availableSeats = $event->total_seats - $event->occupied_seats;
            if (config('app.debug')) {
                Log::info('Event data', [
                    'id' => $event->id,
                    'title' => $event->title,
                    'total_seats' => $event->total_seats,
                    'occupied_seats' => $event->occupied_seats,
                    'available_seats' => $availableSeats,
                ]);
            }
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'practical_parts' => $event->practical_parts,
                'methodologies' => $event->methodologies,
                'photo' => $event->photo,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'total_seats' => $event->total_seats,
                'occupied_seats' => $event->occupied_seats,
                'available_seats' => max(0, $availableSeats),
            ];
        });

        return Inertia::render('EventsPage', [
            'events' => $events,
            'auth' => ['user' => Auth::user()],
        ])->withViewData('layout', 'Layouts/AppLayout');
    }

    public function registerForEvent(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        return DB::transaction(function () use ($request, $event) {
            $event->refresh();

            if ($event->available_seats <= 0) {
                Log::warning('Registration attempt failed: no available seats', [
                    'event_id' => $event->id,
                    'total_seats' => $event->total_seats,
                    'occupied_seats' => $event->occupied_seats,
                ]);
                return redirect()->back()->withErrors(['message' => 'Нет свободных мест!']);
            }

            $registrationData = [
                'event_id' => $event->id,
            ];

            if (Auth::check()) {
                $user = Auth::user();
                $registrationData = array_merge($registrationData, [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '',
                ]);
            } else {
                $registrationData = array_merge($registrationData, $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'email', 'max:255'],
                    'phone' => ['required', 'string', 'max:20'],
                ]));
            }

            EventRegistration::create($registrationData);
            $event->increment('occupied_seats');

            Log::info('User registered for event', [
                'event_id' => $event->id,
                'user_id' => $registrationData['user_id'] ?? null,
            ]);

            return redirect()->back()->with('success', 'Вы успешно записаны!');
        });
    }

    public function showEventDetails($id)
    {
        $event = Event::with('teachers')->findOrFail($id);

        $availableSeats = $event->total_seats - $event->occupied_seats;

        if (config('app.debug')) {
            Log::info('Event details data', [
                'id' => $event->id,
                'title' => $event->title,
                'total_seats' => $event->total_seats,
                'occupied_seats' => $event->occupied_seats,
                'available_seats' => $availableSeats,
            ]);
        }

        return Inertia::render('EventDetails', [
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'practical_parts' => $event->practical_parts,
                'methodologies' => $event->methodologies,
                'photo' => $event->photo,
                'start_date' => $event->start_date,
                'end_date' => $event->end_date,
                'location' => $event->location,
                'duration' => $event->duration,
                'total_seats' => $event->total_seats,
                'occupied_seats' => $event->occupied_seats,
                'available_seats' => max(0, $availableSeats),
                'teachers' => $event->teachers->map(fn($teacher) => [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                ]),
            ],
            'auth' => ['user' => Auth::user()],
        ]);
    }

    public function storeTeacher(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('teachers', 'public');
        }

        Teacher::create([
            'name' => $validated['name'],
            'bio' => $validated['bio'],
            'photo' => $photoPath,
        ]);

        return redirect()->back()->with('success', 'Преподаватель успешно добавлен!');
    }

    public function deleteTeacher($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->back()->with('success', 'Преподаватель успешно удален!');
    }
}
