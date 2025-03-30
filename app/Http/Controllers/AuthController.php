<?php
namespace App\Http\Controllers;

use App\Models\{Event, EventRegistration, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Log, DB};
use Inertia\Inertia;

class AuthController extends Controller
{
    private const DASHBOARD_PATH = '/dashboard';
    private const HOME_PATH = '/';

    public function login(Request $request)
    {
        if (Auth::attempt($this->validateLogin($request))) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Проверяем роль пользователя
            if ($user->role === 'admin') {
                return Inertia::render('AdminPanel', [
                    'user' => fn() => $user,
                    'csrf_token' => csrf_token(),
                ]);
            }

            // Для обычных пользователей перенаправляем на главную страницу
            return Inertia::render('MainPage', [
                'title' => 'Главная',
                'user' => fn() => $user,
                'csrf_token' => csrf_token(),
            ]);
        }
        return back()->withErrors(['email' => 'Неверные учетные данные']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::render('MainPage', [
            'title' => 'Главная',
            'csrf_token' => csrf_token(),
        ]);
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

        // После регистрации перенаправляем в зависимости от роли
        return redirect($user->role === 'admin' ? self::DASHBOARD_PATH : self::HOME_PATH);
    }

    private function validateRegistration(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^\+7 \(\d{3}\)-\d{3}-\d{2}-\d{2}$/'],
            'photo' => ['nullable', 'image', 'max:2048'], // Максимум 2MB
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

    public function showDashboard()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return Inertia::render('AdminPanel', [
                'user' => fn() => $user,
            ]);
        }
        return redirect(self::HOME_PATH); // Обычных пользователей перенаправляем на главную
    }

    public function showAdminPanel(Request $request)
    {
        $selectedEventId = $request->input('event_id');
        $events = Event::query()->select(['id', 'title'])->get();
        $registrations = $selectedEventId
            ? EventRegistration::query()
                ->where('event_id', $selectedEventId)
                ->select(['id', 'name', 'email', 'phone', 'created_at'])
                ->get()
            : collect();

        if (config('app.debug')) {
            Log::info('Admin panel data', [
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

    public function storeEvent(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'practical_parts' => ['required', 'array', 'min:1'],
            'methodologies' => ['required', 'array', 'min:1'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'total_seats' => ['required', 'integer', 'min:1'],
        ]);

        $data['photo'] = $request->file('photo')?->store('events', 'public');
        $data['occupied_seats'] = 0;

        Event::create($data);

        return redirect()->back()->with('success', 'Мероприятие успешно добавлено');
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
                'available_seats' => max(0, $availableSeats), // Гарантируем, что не будет отрицательных значений
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
                    'phone' => $user->phone ?? '', // Используем телефон из профиля
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
}
