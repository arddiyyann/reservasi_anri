<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminReservationController extends Controller
{
    public function index(Request $request)
    {
        $q = Reservation::query()
            ->with(['user:id,name,email', 'service:id,name', 'slot:id,date,start_time,end_time'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        return response()->json(['data' => $q->get()]);
    }

    public function approve(Reservation $reservation, Request $request)
    {
        if ($reservation->status !== 'pending') {
            return response()->json(['message' => 'Hanya pending yang bisa di-approve.'], 422);
        }

        $reservation->update([
            'status' => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return response()->json(['message' => 'Approved', 'data' => $reservation]);
    }

    public function reject(Reservation $reservation, Request $request)
    {
        if ($reservation->status !== 'pending') {
            return response()->json(['message' => 'Hanya pending yang bisa di-reject.'], 422);
        }

        $reservation->update([
            'status' => 'rejected',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        return response()->json(['message' => 'Rejected', 'data' => $reservation]);
    }
}
