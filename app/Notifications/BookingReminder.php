<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class BookingReminder extends Notification
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return [WebPushChannel::class];
    }

    public function toWebPush(object $notifiable, Notification $notification): WebPushMessage
    {
        $b = $this->booking;
        $time = $b->scheduled_at->format('H:i');

        return (new WebPushMessage)
            ->title("Booking 1 jam lagi - pukul {$time}")
            ->body("{$b->customer_name} akan potong ({$b->service->name}) dengan {$b->barber->name}.")
            ->icon('/favicon.ico')
            ->options(['TTL' => 3600])
            ->data(['url' => route('bookings.index', ['date' => $b->scheduled_at->toDateString()])]);
    }
}
