<?php

use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\TeacherTeachingController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\YearController;
use App\Http\Controllers\Api\UserControllerApi;
use App\Http\Controllers\Api\RoleControllerApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestEmailController;

Route::middleware(['api.logger'])->group(function () {
    //Years

    Route::get('/years', [YearController::class, 'index']);
    Route::post('/years', [YearController::class, 'store']);
    Route::get('/years/{year}', [YearController::class, 'show']);
    Route::post('/years/{id}/clone', [YearController::class, 'clone']);

    //Groupes

        //Récupération des données

        Route::get('/groupes/{year}', [GroupController::class, 'index']);
        Route::get('/groupes/promotion/{academic_promotion}', [GroupController::class, 'getByPromotion']);
        Route::get('/groupes/groupe/{group}', [GroupController::class, 'getByGroup']);
        Route::get('/groupes/sous-groupe/{subgroup}', [GroupController::class, 'getBySubgroup']);

        //Création des données

        Route::post('/groupes/promotion/{year}', [GroupController::class, 'storePromotion']);
        Route::post('/groupes/groupe/{promotion}', [GroupController::class, 'storeGroup']);
        Route::post('/groupes/sous-groupe/{group}', [GroupController::class, 'storeSubgroup']);

        //Modification des données

        Route::put('/groupes/promotion/{promotion}', [GroupController::class, 'updatePromotion']);
        Route::put('/groupes/groupe/{group}', [GroupController::class, 'updateGroup']);
        Route::put('/groupes/sous-groupe/{subgroup}', [GroupController::class, 'updateSubgroup']);

        //Suppression des données

        Route::delete('/groupes/promotion/{promotion}', [GroupController::class, 'deletePromotion']);
        Route::delete('/groupes/groupe/{group}', [GroupController::class, 'deleteGroup']);
        Route::delete('/groupes/sous-groupe/{subgroup}', [GroupController::class, 'deleteSubgroup']);

    //Enseignants

        //Récupération des données

        Route::get('/enseignants/{year}', [TeacherTeachingController::class, 'getTeachers']);
        Route::get('/enseignements/{year}', [TeacherTeachingController::class, 'getTeachings']);
        Route::get('/enseignement/enseignants/{teaching}', [TeacherTeachingController::class, 'getTeachersByTeaching']);
        Route::get('/enseignant/enseignements/{teacher}', [TeacherTeachingController::class, 'getTeachingsByTeacher']);
        Route::get('/enseignant/{teacher}', [TeacherTeachingController::class, 'getTeacher']);
        Route::get('/enseignement/{teaching}', [TeacherTeachingController::class, 'getTeaching']);
        Route::get('/enseignement/{teaching}/check-hours', [TeacherTeachingController::class, 'checkTeachingHours']);

        Route::get('/enseignant/enseignement/{teacher}/{teaching}', [TeacherTeachingController::class, 'getTeacherTeaching']);

        //Création des données

        Route::post('/enseignant/{year}', [TeacherTeachingController::class, 'storeTeacher']);
        Route::post('/enseignement/{year}', [TeacherTeachingController::class, 'storeTeaching']);

        Route::post('/enseignant/enseignement/{teacher_id}/{teaching_id}', [TeacherTeachingController::class, 'storeTeacherTeaching']);


        //Modification des données

        Route::put('/enseignant/{teacher}', [TeacherTeachingController::class, 'updateTeacher']);
        Route::put('/enseignement/{teaching}', [TeacherTeachingController::class, 'updateTeaching']);

        //Suppression des données

        Route::delete('/enseignant/{teacher}', [TeacherTeachingController::class, 'deleteTeacher']);
        Route::delete('/enseignement/{teaching}', [TeacherTeachingController::class, 'deleteTeaching']);

        Route::delete('/enseignant/enseignement/{teacher}/{teaching}', [TeacherTeachingController::class, 'deleteTeacherTeaching']);

    //Labels

        Route::get('/labels', [LabelController::class, 'index']);
        Route::get('/labels/{label_id}', [LabelController::class, 'getLabel']);
        Route::put('/labels/{label_id}', [LabelController::class, 'updateLabel']);

        Route::get('/roles', [RoleControllerApi::class, 'index']);
        Route::get('/users', [UserControllerApi::class, 'index']);
        Route::post('/users', [UserControllerApi::class, 'store']);
        Route::put('/users/{user}', [UserControllerApi::class, 'update']);
        Route::delete('/users/{user}', [UserControllerApi::class, 'destroy']);
        Route::post('/users/{user}/create-or-reset-password', [UserControllerApi::class, 'createOrResetPassword']);

    //Calendrier

        Route::post('/calendrier', [CalendarController::class, 'storeSlot']);
        Route::get('/calendrier/{id}', [CalendarController::class, 'getCalendarData']);
});
