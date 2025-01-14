<?php

namespace App\Http\Controllers;

use App\Mail\TeacherNotificationMail;
use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function sendTestEmail()
    {
        $teacher = \App\Models\Teacher::first(); // Assurez-vous qu'il y a un enseignant dans la base de données
        Mail::to('sofian.smimid@etu.unilim.fr')->send(new TeacherNotificationMail($teacher));
        return 'Email de test envoyé';
    }
}