<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Enums\UserRole;
use App\Exports\ReportExport;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        return view('owner.dashboard', [
            'revenueToday' => Payment::whereDate('paid_at', $today)->sum('amount'),
            'bookingsToday' => Booking::whereDate('scheduled_at', $today)->count(),
            'pendingBookings' => Booking::where('status', BookingStatus::Pending->value)->count(),
            'barberCount' => User::where('role', UserRole::Barber->value)->where('is_active', true)->count(),
            'recentBookings' => Booking::with(['barber', 'service'])->latest()->take(8)->get(),
        ]);
    }

    public function reports()
    {
        return view('owner.reports', $this->reportData());
    }

    public function exportPdf()
    {
        $pdf = Pdf::loadView('owner.reports-pdf', $this->reportData())->setPaper('a4');

        return $pdf->download('laporan-dos-'.now()->format('Y-m-d').'.pdf');
    }

    public function exportXlsx()
    {
        return Excel::download(new ReportExport($this->reportData()), 'laporan-dos-'.now()->format('Y-m-d').'.xlsx');
    }

    // Sumber tunggal data laporan untuk view, PDF, dan XLSX.
    private function reportData(): array
    {
        $revenueByDay = Payment::selectRaw('DATE(paid_at) as day, SUM(amount) as total')
            ->groupBy('day')->orderByDesc('day')->take(14)->get();

        $revenueByBarber = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('users', 'bookings.barber_id', '=', 'users.id')
            ->selectRaw('users.name as barber, SUM(payments.amount) as total')
            ->groupBy('users.name')->orderByDesc('total')->get();

        $revenueByService = Payment::join('bookings', 'payments.booking_id', '=', 'bookings.id')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->selectRaw('services.name as service, COUNT(*) as qty, SUM(payments.amount) as total')
            ->groupBy('services.name')->orderByDesc('total')->get();

        return compact('revenueByDay', 'revenueByBarber', 'revenueByService');
    }
}
