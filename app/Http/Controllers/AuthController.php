<?php
// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return Inertia::render('Login');
    }

    public function showRegister()
    {
        return Inertia::render('Register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);

        return redirect('/dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Неверные учетные данные',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    public function showDashboard()
    {
        return Inertia::render('AdminPanel', [
            'user' => Auth::user(),
        ]);
    }

    public function showAdminPanel(Request $request)
    {
        $events = Event::all(['id', 'title']);
        $selectedEventId = $request->input('event_id');

        \Log::info('Admin panel requested', [
            'selectedEventId' => $selectedEventId,
            'request_all' => $request->all(),
        ]);

        $registrations = $selectedEventId
            ? EventRegistration::where('event_id', $selectedEventId)->get(['id', 'name', 'email', 'phone', 'created_at'])
            : collect([]); // Используем коллекцию вместо массива

        \Log::info('Registrations fetched', [
            'selectedEventId' => $selectedEventId,
            'registrations' => $registrations->toArray(), // Теперь toArray() работает
        ]);

        return Inertia::render('Dashboard', [
            'user' => Auth::user(),
            'events' => $events,
            'registrations' => $registrations,
            'selectedEventId' => $selectedEventId,
        ]);
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'practical_parts' => 'required|array|min:1',
            'methodologies' => 'required|array|min:1',
            'photo' => 'nullable|image|max:2048', // Максимум 2MB
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'total_seats' => 'required|integer|min:1',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('events', 'public');
        }

        Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'practical_parts' => $request->practical_parts,
            'methodologies' => $request->methodologies,
            'photo' => $photoPath,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_seats' => $request->total_seats,
            'occupied_seats' => 0,
        ]);

        return redirect()->back()->with('success', 'Мероприятие успешно добавлено');
    }
    public function showEvents()
    {
        $events = Event::all();
        return Inertia::render('EventsPage', [
            'events' => $events,
            'auth' => auth()->user() ? ['user' => auth()->user()] : ['user' => null],
        ])->withViewData('layout', 'Layouts/AppLayout');
    }

    public function registerForEvent(Request $request, $eventId)
    {
        file_put_contents(storage_path('logs/debug.log'), "registerForEvent called: " . json_encode([
                'eventId' => $eventId,
                'request' => $request->all(),
                'auth' => Auth::check(),
            ]) . PHP_EOL, FILE_APPEND);

        $event = Event::findOrFail($eventId);
        if ($event->total_seats - $event->occupied_seats <= 0) {
            file_put_contents(storage_path('logs/debug.log'), "No seats available: $eventId" . PHP_EOL, FILE_APPEND);
            return redirect()->back()->withErrors(['message' => 'Нет свободных мест!']);
        }

        if (!Auth::check()) {
            file_put_contents(storage_path('logs/debug.log'), "Processing non-authenticated user: " . json_encode($request->all()) . PHP_EOL, FILE_APPEND);

            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
            ]);

            EventRegistration::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'event_id' => $eventId,
            ]);
            file_put_contents(storage_path('logs/debug.log'), "Event registration saved: " . json_encode($data) . PHP_EOL, FILE_APPEND);
        }

        $event->update(['occupied_seats' => $event->occupied_seats + 1]);
        file_put_contents(storage_path('logs/debug.log'), "Event seats updated: occupied_seats = " . $event->occupied_seats . PHP_EOL, FILE_APPEND);

        return redirect()->back()->with('success', 'Вы успешно записаны на мероприятие!');
    }
}
