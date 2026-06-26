<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'customer_name', 'customer_phone', 'barber_id',
    'service_id', 'scheduled_at', 'status', 'notes', 'reminder_sent_at',
])]
class Booking extends Model
{
    protected function casts(): array
    {
        return ['scheduled_at' => 'datetime', 'reminder_sent_at' => 'datetime'];
    }

    public function barber()
    {
        return $this->belongsTo(User::class, 'barber_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function isPaid(): bool
    {
        return $this->payment()->exists();
    }
}
