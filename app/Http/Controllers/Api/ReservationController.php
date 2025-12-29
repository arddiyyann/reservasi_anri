<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\ServiceSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'service_slot_id' => ['required', 'integer', 'exists:service_slots,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = $request->user();

        $reservation = DB::transaction(function () use ($request, $user) {
            /** @var ServiceSlot $slot */
            $slot = ServiceSlot::query()
                ->lockForUpdate()
                ->findOrFail($request->service_slot_id);

            if ($slot->is_closed) {
                abort(422, 'Slot sedang ditutup.');
            }

            // Cegah double booking user di slot yang sama
            $exists = Reservation::query()
                ->where('user_id', $user->id)
                ->where('service_slot_id', $slot->id)
                ->whereIn('status', ['pending', 'approved', 'checked_in'])
                ->exists();

            if ($exists) {
                abort(422, 'Kamu sudah reservasi di slot ini.');
            }

            // Cek kuota
            $activeCount = Reservation::query()
                ->where('service_slot_id', $slot->id)
                ->whereIn('status', ['pending', 'approved', 'checked_in'])
                ->count();

            if ($activeCount >= $slot->capacity) {
                abort(422, 'Kuota slot sudah penuh.');
            }

            $code = 'ANRI-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

            return Reservation::create([
                'reservation_code' => $code,
                'user_id' => $user->id,
                'service_id' => $slot->service_id,
                'service_slot_id' => $slot->id,
                'status' => 'pending', // nanti bisa auto-approve kalau mau
                'notes' => $request->notes,
            ]);
        });

        return response()->json([
            'message' => 'Reservasi berhasil dibuat.',
            'data' => $reservation,
        ], 201);
    }

    public function myReservations(Request $request)
    {
        $user = $request->user();

        $data = Reservation::query()
            ->with(['service:id,name', 'slot:id,service_id,date,start_time,end_time'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $data]);
    }
}
