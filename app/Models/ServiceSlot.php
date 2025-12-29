<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceSlot extends Model
{
    protected $fillable = [
        'service_id',
        'date',
        'start_time',
        'end_time',
        'capacity',
        'is_closed'
    ];

    protected $casts = [
        'date' => 'date',
        'is_closed' => 'boolean',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function staff()
    {
        return $this->belongsToMany(User::class, 'service_slot_staff')
            ->withTimestamps();
    }
}
