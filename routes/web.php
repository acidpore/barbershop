<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing', ['services' => Service::where('is_active', true)->get()]);
})->name('landing');

// Redirect ke area sesuai role setelah login.
Route::get('/dashboard', function () {
    return match (Auth::user()->role) {
        'owner' => redirect()->route('owner.dashboard'),
        'kasir' => redirect()->route('kasir.bookings'),
        'barber' => redirect()->route('barber.queue'),
    };
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    // Simpan langganan Web Push milik user (untuk reminder booking).
    Route::post('/push-subscription', function (\Illuminate\Http\Request $request) {
        $data = $request->validate([
            'endpoint' => ['required', 'string'],
            'keys.p256dh' => ['required', 'string'],
            'keys.auth' => ['required', 'string'],
        ]);
        $request->user()->updatePushSubscription($data['endpoint'], $data['keys']['p256dh'], $data['keys']['auth']);

        return response()->noContent();
    })->name('push.subscribe');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Owner: akses penuh.
Route::middleware(['auth', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');
    Route::get('/reports/export/pdf', [DashboardController::class, 'exportPdf'])->name('reports.pdf');
    Route::get('/reports/export/xlsx', [DashboardController::class, 'exportXlsx'])->name('reports.xlsx');
    Route::resource('users', UserController::class)->except('show');
    Route::resource('services', ServiceController::class)->except('show');
    Route::resource('payment-methods', PaymentMethodController::class)->only(['store', 'update', 'destroy']);
});

// Owner + Kasir: kelola booking dan pembayaran.
Route::middleware(['auth', 'role:owner,kasir'])->group(function () {
    Route::resource('bookings', BookingController::class)->except('show');
    Route::get('/kasir/bookings', [BookingController::class, 'index'])->name('kasir.bookings');
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/bookings/{booking}/pay', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/bookings/{booking}/pay', [PaymentController::class, 'store'])->name('payments.store');
});

// Barber: antrian dan update status layanan.
Route::middleware(['auth', 'role:barber'])->prefix('barber')->name('barber.')->group(function () {
    Route::get('/queue', [BookingController::class, 'queue'])->name('queue');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.status');
});

require __DIR__.'/auth.php';
