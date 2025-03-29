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
            return Inertia::render('AdminPanel', [
                'user' => fn() => Auth::user(), // Передаём user явно
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
        $user = User::create([...$data, 'password' => bcrypt($data['password'])]);
        Auth::login($user);
        return redirect(self::DASHBOARD_PATH);
    }

    private function validateRegistration(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
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
        return Inertia::render('AdminPanel', [
            'user' => fn() => Auth::user(),
        ]);
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
        return Inertia::render('EventsPage', [
            'events' => fn() => Event::all(),
            'auth' => ['user' => Auth::user()],
        ])->withViewData('layout', 'Layouts/AppLayout');
    }

    public function registerForEvent(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        return DB::transaction(function () use ($request, $event) {
            if ($event->available_seats <= 0) {
                return redirect()->back()->withErrors(['message' => 'Нет свободных мест!']);
            }

            if (!Auth::check()) {
                EventRegistration::create(
                    $request->validate([
                        'name' => ['required', 'string', 'max:255'],
                        'email' => ['required', 'email', 'max:255'],
                        'phone' => ['required', 'string', 'max:20'],
                    ]) + ['event_id' => $event->id]
                );
            }

            $event->increment('occupied_seats');
            return redirect()->back()->with('success', 'Вы успешно записаны!');
        });
    }
}
