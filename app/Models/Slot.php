<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $fillable = [
        'duration',
        'teacher_id',
        'teaching_id',
        'substitute_teacher_id',
        'academic_promotion_id',
        'academic_group_id',
        'academic_subgroup_id',
        'is_neutralized',
        'week_id',
        'type', // Ajout du type
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($slot) {
            foreach ($slot->teachers as $teacher) {
                app(\App\Services\TeacherNotificationService::class)->handleModification($teacher);
            }
        });
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function substituteTeacher()
    {
        return $this->belongsTo(Teacher::class, 'substitute_teacher_id');
    }

    public function teaching()
    {
        return $this->belongsTo(Teaching::class);
    }

    public function academicPromotion()
    {
        return $this->belongsTo(AcademicPromotion::class);
    }

    public function academicGroup()
    {
        return $this->belongsTo(AcademicGroup::class);
    }

    public function academicSubgroup()
    {
        return $this->belongsTo(AcademicSubgroup::class);
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }
}
