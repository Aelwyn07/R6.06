<?php

namespace App\Services;

use App\Models\Teacher;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeacherNotificationMail;

class TeacherNotificationService
{
    protected $timers = [];

    public function handleModification(Teacher $teacher)
    {
        $teacherId = $teacher->id;

        if (isset($this->timers[$teacherId])) {
            // Réinitialiser le timer
            clearTimeout($this->timers[$teacherId]);
        }

        // Définir un nouveau timer
        $this->timers[$teacherId] = setTimeout(function() use ($teacher) {
            $this->sendNotification($teacher);
            unset($this->timers[$teacher->id]);
        }, 300000); // 5 minutes
    }

    protected function sendNotification(Teacher $teacher)
    {
        Mail::to($teacher->email)->send(new TeacherNotificationMail($teacher));
    }
}