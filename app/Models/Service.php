<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'description', 'is_active'];

    public function slots()
    {
        return $this->hasMany(ServiceSlot::class);
    }
}
