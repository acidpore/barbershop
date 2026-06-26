<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payments.index', [
            'unpaid' => Booking::with(['barber', 'service'])
                ->where('status', BookingStatus::Done->value)
                ->whereDoesntHave('payment')
                ->latest('scheduled_at')
                ->get(),
            'recent' => Payment::with(['booking.service', 'cashier'])
                ->latest('paid_at')
                ->take(15)
                ->get(),
        ]);
    }

    public function create(Booking $booking)
    {
        abort_if($booking->isPaid(), 404);

        return view('payments.form', [
            'booking' => $booking->load('service', 'barber'),
            'methods' => PaymentMethod::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->isPaid(), 404);

        $data = $request->validate([
            'method' => ['required', Rule::in(PaymentMethod::where('is_active', true)->pluck('name'))],
        ]);

        // Harga di-snapshot dari paket saat pembayaran agar laporan tetap akurat.
        Payment::create([
            'booking_id' => $booking->id,
            'cashier_id' => auth()->id(),
            'amount' => $booking->service->price,
            'method' => $data['method'],
            'paid_at' => now(),
        ]);

        return redirect()->route('payments.index')->with('status', 'Pembayaran berhasil dicatat.');
    }
}
