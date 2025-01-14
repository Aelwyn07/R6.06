<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicGroup extends Model
{
    protected $fillable = [
        'name',
        'academic_promotion_id',
    ];

    public function academicPromotion()
    {
        return $this->belongsTo(AcademicPromotion::class);
    }

    public function academicSubgroups()
    {
        return $this->hasMany(AcademicSubgroup::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($group) {
            // Delete associated slots first
            $group->slots()->delete();
        });
    }
}
