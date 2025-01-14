<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    protected $fillable = [
        'message',
        'year_id',
    ];

    public function year()
    {
        return $this->belongsTo(Year::class);
    }
}
