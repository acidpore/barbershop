<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Enums\UserRole;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders';

    protected $description = 'Kirim push notification 1 jam sebelum jadwal booking.';

    public function handle(): int
    {
        // Booking aktif yang jadwalnya dalam 60 menit ke depan dan belum diingatkan.
        $bookings = Booking::with(['barber', 'service'])
            ->whereNull('reminder_sent_at')
            ->whereIn('status', [BookingStatus::Pending->value, BookingStatus::InProgress->value])
            ->whereBetween('scheduled_at', [now(), now()->addMinutes(60)])
            ->get();

        $owners = User::where('role', UserRole::Owner->value)->where('is_active', true)->get();

        foreach ($bookings as $booking) {
            // Notif ke barber yang bertugas + semua owner.
            $recipients = $owners->concat([$booking->barber])->filter()->unique('id');
            Notification::send($recipients, new BookingReminder($booking));

            $booking->update(['reminder_sent_at' => now()]);
            $this->info("Reminder terkirim: {$booking->customer_name} ({$booking->scheduled_at->format('H:i')}).");
        }

        $this->info("Selesai. {$bookings->count()} reminder diproses.");

        return self::SUCCESS;
    }
}
