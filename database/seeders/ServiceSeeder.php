<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceSlot;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $service = Service::create([
            'name' => 'Ruang Rapat',
            'description' => 'Reservasi kunjungan Bimbingan Teknis dan Konsultasi',
            'is_active' => true,
        ]);

        // buat slot untuk hari ini (Asia/Jakarta)
        $date = now()->toDateString();

        $slots = [
            ['09:00:00', '10:00:00', 10],
            ['10:00:00', '11:00:00', 10],
            ['13:00:00', '14:00:00', 10],
        ];

        foreach ($slots as [$start, $end, $cap]) {
            ServiceSlot::create([
                'service_id' => $service->id,
                'date' => $date,
                'start_time' => $start,
                'end_time' => $end,
                'capacity' => $cap,
                'is_closed' => false,
            ]);
        }
    }
}
