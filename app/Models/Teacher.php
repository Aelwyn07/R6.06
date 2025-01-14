<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'acronym',
        'first_name',
        'last_name',
        'year_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($teacher) {
            app(\App\Services\TeacherNotificationService::class)->handleModification($teacher);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teachings()
    {
        return $this->belongsToMany(Teaching::class, 'teachers_teachings');
    }

    public function year()
    {
        return $this->belongsTo(Year::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }
}
