<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicPromotion extends Model
{
    protected $fillable = [
        'name',
        'year_id',
    ];

    public function academicGroups()
    {
        return $this->hasMany(AcademicGroup::class);
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }
}
