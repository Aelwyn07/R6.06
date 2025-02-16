<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProvisionnalCalendarEditorController;
use App\Http\Controllers\ProvisionnalCalendarSettingsController;
use App\Http\Controllers\TeachersTeachingsController;
use App\Http\Controllers\ProvisionnalCalendarReaderController;
use App\Http\Controllers\WorkInProgressController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestEmailController;

const ROLE_READER = 'role:reader';
const ROLE_EXTENDED_READER = 'role:extended_reader';
const ROLE_ADMIN = 'role:admin';
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

Route::get('/logout', [AuthController::class, 'logout'])->middleware(['auth'])->name('logout');

Route::get('/', [IndexController::class, 'show'])->middleware(['auth'])->name('index');

Route::get('/calendrier-previsionnel', [ProvisionnalCalendarReaderController::class, 'show'])
    ->middleware(['auth', ROLE_READER, ROLE_EXTENDED_READER])
    ->name('provisionnal_calendar');

Route::get('/edt', [WorkInProgressController::class, 'show'])
    ->middleware(['auth', ROLE_READER, ROLE_EXTENDED_READER, ROLE_ADMIN])
    ->name('edt');

Route::get('/service', [WorkInProgressController::class, 'show'])
    ->middleware(['auth', ROLE_READER, ROLE_EXTENDED_READER, ROLE_ADMIN])
    ->name('service');

Route::get('/send-test-email', [TestEmailController::class, 'sendTestEmail']);

Route::middleware(['auth', ROLE_ADMIN])->group(function () {
    Route::get('/calendrier-previsionnel/groupes', [GroupsController::class, 'show'])
        ->name('provisionnal_calendar.groups');

    Route::get('/calendrier-previsionnel/enseignants-enseignements', [TeachersTeachingsController::class, 'show'])
        ->name('provisionnal_calendar.teachers_teachings');

    Route::get('/calendrier-previsionnel/editeur', [ProvisionnalCalendarEditorController::class, 'show'])
        ->name('provisionnal_calendar.editor');

    Route::get('/configurations', function () {
        return redirect()->route('provisionnal_calendar.settings.labels');
    })->name('provisionnal_calendar.settings');

    Route::get('/configurations/labels', [ProvisionnalCalendarSettingsController::class, 'showLabels'])
        ->name('provisionnal_calendar.settings.labels');

    Route::get('/configurations/utilisateurs', [ProvisionnalCalendarSettingsController::class, 'showUsers'])
        ->name('provisionnal_calendar.settings.users');
});
