<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\AdminReservationController;
use App\Http\Controllers\Api\AdminSlotController;
use App\Http\Controllers\Api\AdminSlotStaffController;

Route::get('/ping', fn() => response()->json(['ok' => true]));
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/me', function (\Illuminate\Http\Request $request) {
    return $request->user();
});
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{service}/slots', [ServiceController::class, 'slots']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/my/reservations', [ReservationController::class, 'myReservations']);
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/reservations', [AdminReservationController::class, 'index']);
    Route::post('/reservations/{reservation}/approve', [AdminReservationController::class, 'approve']);
    Route::post('/reservations/{reservation}/reject', [AdminReservationController::class, 'reject']);
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::post('/slots/{slot}/close', [AdminSlotController::class, 'close']);
    Route::post('/slots/{slot}/open', [AdminSlotController::class, 'open']);
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::post('/slots/{slot}/assign-staff', [AdminSlotStaffController::class, 'assign']);
    Route::get('/slots/{slot}/staff', [AdminSlotStaffController::class, 'show']);
});

Route::middleware('auth:sanctum')->get('/staff/my-slots', function (\Illuminate\Http\Request $request) {
    $request->validate(['date' => ['required', 'date']]);

    $user = $request->user();

    if (!in_array($user->role, ['staff', 'admin'], true)) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

    $slots = $user->assignedSlots()
        ->with('service:id,name')
        ->whereDate('date', $request->date)
        ->orderBy('start_time')
        ->get();

    return response()->json(['data' => $slots]);
});



Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
