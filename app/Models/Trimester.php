<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trimester extends Model
{
    protected $fillable = [
        'trimester_number',
        'year_id'
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function teachings()
    {
        return $this->hasMany(Teaching::class);
    }
}
