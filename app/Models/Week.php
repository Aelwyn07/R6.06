<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Week extends Model
{
    protected $fillable = [
        'name',
        'week_number',
        'year_id',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }
}
