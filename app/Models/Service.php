<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'description', 'price', 'duration_minutes', 'is_active'])]
class Service extends Model
{
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
