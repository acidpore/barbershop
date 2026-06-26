<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->date ? Carbon::parse($request->date)->startOfDay() : Carbon::today();
        $weekStart = $selectedDate->copy()->startOfWeek(Carbon::MONDAY);
        $weekDays = collect(range(0, 6))->map(fn ($i) => $weekStart->copy()->addDays($i));

        $dayBookings = Booking::with(['barber', 'service', 'payment'])
            ->whereDate('scheduled_at', $selectedDate)
            ->orderBy('scheduled_at')
            ->get();

        // Jumlah booking per hari (untuk titik penanda di strip minggu).
        $weekCounts = Booking::whereBetween('scheduled_at', [$weekStart, $weekStart->copy()->addDays(7)])
            ->selectRaw('DATE(scheduled_at) as d, COUNT(*) as c')
            ->groupBy('d')->pluck('c', 'd');

        // Tata letak antikolisi: tempatkan tiap booking di lajur pertama yang sudah kosong.
        $laneEnds = [];
        foreach ($dayBookings as $booking) {
            $start = $booking->scheduled_at->hour * 60 + $booking->scheduled_at->minute;
            $end = $start + max($booking->service->duration_minutes, 30);
            $lane = collect($laneEnds)->search(fn ($laneEnd) => $start >= $laneEnd);
            if ($lane === false) {
                $lane = count($laneEnds);
            }
            $laneEnds[$lane] = $end;
            $booking->lane = $lane;
            $booking->startMin = $start;
            $booking->durationMin = max($booking->service->duration_minutes, 30);
        }
        $laneCount = max(count($laneEnds), 1);

        // Daftar semua booking (tabel di bawah kalender) dengan pencarian & filter status.
        $bookings = Booking::with(['barber', 'service', 'payment'])
            ->when($request->status, fn ($q, $status) => $q->where('status', $status))
            ->when($request->search, fn ($q, $search) => $q->where('customer_name', 'like', "%{$search}%"))
            ->latest('scheduled_at')
            ->paginate(10)
            ->withQueryString();

        return view('bookings.index', compact('selectedDate', 'weekDays', 'weekCounts', 'dayBookings', 'laneCount', 'bookings'));
    }

    public function create()
    {
        return view('bookings.form', [
            'booking' => new Booking,
            'barbers' => $this->barbers(),
            'services' => Service::where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        Booking::create($this->validateData($request));

        return redirect()->route('bookings.index')->with('status', 'Booking berhasil dibuat.');
    }

    public function edit(Booking $booking)
    {
        return view('bookings.form', [
            'booking' => $booking,
            'barbers' => $this->barbers(),
            'services' => Service::where('is_active', true)->get(),
        ]);
    }

    public function update(Request $request, Booking $booking)
    {
        $booking->update($this->validateData($request));

        return redirect()->route('bookings.index')->with('status', 'Booking berhasil diperbarui.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return back()->with('status', 'Booking berhasil dihapus.');
    }

    public function queue()
    {
        return view('barber.queue', [
            'bookings' => Booking::with('service')
                ->where('barber_id', auth()->id())
                ->whereIn('status', [BookingStatus::Pending->value, BookingStatus::InProgress->value])
                ->orderBy('scheduled_at')
                ->get(),
        ]);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        abort_unless($booking->barber_id === auth()->id(), 403);

        $data = $request->validate([
            'status' => ['required', Rule::in(['in_progress', 'done', 'cancelled'])],
        ]);

        $booking->update($data);

        return back()->with('status', 'Status booking diperbarui.');
    }

    private function barbers()
    {
        return User::where('role', UserRole::Barber->value)->where('is_active', true)->orderBy('name')->get();
    }

    private function validateData(Request $request): array
    {
        return $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'barber_id' => ['required', Rule::exists('users', 'id')->where('role', UserRole::Barber->value)],
            'service_id' => ['required', Rule::exists('services', 'id')],
            'scheduled_at' => ['required', 'date'],
            'status' => ['required', Rule::in(BookingStatus::values())],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
