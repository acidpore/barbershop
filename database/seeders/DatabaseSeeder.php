<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['name' => 'Owner', 'email' => 'owner@mail.com', 'role' => 'owner'],
            ['name' => 'Kasir', 'email' => 'kasir@mail.com', 'role' => 'kasir'],
            ['name' => 'Nanda', 'email' => 'barber@mail.com', 'role' => 'barber'],
            ['name' => 'Andi Barber', 'email' => 'barber2@mail.com', 'role' => 'barber'],
            ['name' => 'Riko Barber', 'email' => 'barber3@mail.com', 'role' => 'barber'],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['email' => $account['email']],
                [...$account, 'password' => Hash::make('password'), 'phone' => '08'.fake()->numerify('##########'), 'is_active' => true],
            );
        }

        $services = [
            ['name' => 'Haircut', 'price' => 80000, 'duration_minutes' => 45, 'description' => 'Consultation + Haircut and Wash.'],
            ['name' => 'Haircut and Shave', 'price' => 100000, 'duration_minutes' => 45, 'description' => 'Consultation + Haircut + Wash and Shave.'],
            ['name' => 'Children Haircut', 'price' => 80000, 'duration_minutes' => 30, 'description' => 'Potong rambut khusus anak.'],
            ['name' => 'Beard Trimming/Shave', 'price' => 50000, 'duration_minutes' => 30, 'description' => 'Rapikan dan cukur jenggot.'],
            ['name' => 'Hair Styling', 'price' => 50000, 'duration_minutes' => 20, 'description' => 'Wash + Styling with Hair Product.'],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['name' => $service['name']], $service);
        }

        foreach (['Tunai', 'QRIS', 'Debit BCA', 'Debit BNI', 'Debit BRI', 'Debit Mandiri', 'Transfer'] as $method) {
            PaymentMethod::firstOrCreate(['name' => $method]);
        }

        // Bersihkan transaksi lama agar seeding berulang tidak menumpuk.
        // delete() (bukan truncate) supaya aman dengan foreign key payments->bookings.
        Payment::query()->delete();
        Booking::query()->delete();

        $barbers = User::where('role', 'barber')->get();
        $kasir = User::where('role', 'kasir')->first();
        $allServices = Service::all();
        $methods = ['Tunai', 'QRIS', 'Debit BCA', 'Debit BNI', 'Debit BRI', 'Debit Mandiri', 'Transfer'];

        // 14 hari ke belakang sampai 2 hari ke depan.
        for ($dayOffset = -14; $dayOffset <= 2; $dayOffset++) {
            $date = Carbon::today()->addDays($dayOffset);
            $count = rand(3, 7);

            for ($i = 0; $i < $count; $i++) {
                $service = $allServices->random();
                $barber = $barbers->random();
                $scheduledAt = $date->copy()->setTime(rand(9, 19), [0, 15, 30, 45][rand(0, 3)]);
                $status = $this->statusForDay($dayOffset, $scheduledAt);

                $booking = Booking::create([
                    'customer_name' => fake()->name('male'),
                    'customer_phone' => '08'.fake()->numerify('##########'),
                    'barber_id' => $barber->id,
                    'service_id' => $service->id,
                    'scheduled_at' => $scheduledAt,
                    'status' => $status,
                    'notes' => fake()->boolean(25) ? fake()->sentence(4) : null,
                ]);

                // Booking selesai langsung dibayar.
                if ($status === 'done') {
                    Payment::create([
                        'booking_id' => $booking->id,
                        'cashier_id' => $kasir->id,
                        'amount' => $service->price,
                        'method' => $methods[array_rand($methods)],
                        'paid_at' => $scheduledAt->copy()->addMinutes($service->duration_minutes),
                    ]);
                }
            }
        }
    }

    // Hari lampau: kebanyakan selesai. Hari ini: campur agar antrian barber terisi. Masa depan: pending.
    private function statusForDay(int $dayOffset, Carbon $scheduledAt): string
    {
        if ($dayOffset < 0) {
            return fake()->randomElement(['done', 'done', 'done', 'done', 'cancelled']);
        }

        if ($dayOffset === 0) {
            // Jam sudah lewat: kemungkinan sedang/sudah dikerjakan. Jam belum tiba: masih menunggu.
            return $scheduledAt->isPast()
                ? fake()->randomElement(['done', 'in_progress', 'done'])
                : 'pending';
        }

        // Hari mendatang belum waktunya dikerjakan.
        return 'pending';
    }
}
