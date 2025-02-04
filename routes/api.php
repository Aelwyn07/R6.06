<?php

use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\TeacherTeachingController;
use App\Http\Controllers\Api\LabelController;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\YearController;
use App\Http\Controllers\Api\UserControllerApi;
use App\Http\Controllers\Api\RoleControllerApi;
use Illuminate\Support\Facades\Route;

const GROUPES_GROUPE_GROUP = '/groupes/groupe/{group}';
const GROUPES_SOUS_GROUPE_SUBGROUP = '/groupes/sous-groupe/{subgroup}';
const ENSEIGNANT_TEACHER = '/enseignant/{teacher}';
const ENSEIGNEMENT_TEACHING = '/enseignement/{teaching}';
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
        Route::get(GROUPES_GROUPE_GROUP, [GroupController::class, 'getByGroup']);
        Route::get(GROUPES_SOUS_GROUPE_SUBGROUP, [GroupController::class, 'getBySubgroup']);

        //Création des données

        Route::post('/groupes/promotion/{year}', [GroupController::class, 'storePromotion']);
        Route::post('/groupes/groupe/{promotion}', [GroupController::class, 'storeGroup']);
        Route::post('/groupes/sous-groupe/{group}', [GroupController::class, 'storeSubgroup']);

        //Modification des données

        Route::put('/groupes/promotion/{promotion}', [GroupController::class, 'updatePromotion']);
        Route::put(GROUPES_GROUPE_GROUP, [GroupController::class, 'updateGroup']);
        Route::put(GROUPES_SOUS_GROUPE_SUBGROUP, [GroupController::class, 'updateSubgroup']);

        //Suppression des données

        Route::delete('/groupes/promotion/{promotion}', [GroupController::class, 'deletePromotion']);
        Route::delete(GROUPES_GROUPE_GROUP, [GroupController::class, 'deleteGroup']);
        Route::delete(GROUPES_SOUS_GROUPE_SUBGROUP, [GroupController::class, 'deleteSubgroup']);

    //Enseignants

        //Récupération des données

        Route::get('/enseignants/{year}', [TeacherTeachingController::class, 'getTeachers']);
        Route::get('/enseignements/{year}', [TeacherTeachingController::class, 'getTeachings']);
        Route::get('/enseignement/enseignants/{teaching}', [TeacherTeachingController::class, 'getTeachersByTeaching']);
        Route::get('/enseignant/enseignements/{teacher}', [TeacherTeachingController::class, 'getTeachingsByTeacher']);
        Route::get(ENSEIGNANT_TEACHER, [TeacherTeachingController::class, 'getTeacher']);
        Route::get(ENSEIGNEMENT_TEACHING, [TeacherTeachingController::class, 'getTeaching']);
        Route::get('/enseignement/{teaching}/check-hours', [TeacherTeachingController::class, 'checkTeachingHours']);

        Route::get('/enseignant/enseignement/{teacher}/{teaching}', [TeacherTeachingController::class, 'getTeacherTeaching']);

        //Création des données

        Route::post('/enseignant/{year}', [TeacherTeachingController::class, 'storeTeacher']);
        Route::post('/enseignement/{year}', [TeacherTeachingController::class, 'storeTeaching']);

        Route::post('/enseignant/enseignement/{teacher_id}/{teaching_id}', [TeacherTeachingController::class, 'storeTeacherTeaching']);


        //Modification des données

        Route::put(ENSEIGNANT_TEACHER, [TeacherTeachingController::class, 'updateTeacher']);
        Route::put(ENSEIGNEMENT_TEACHING, [TeacherTeachingController::class, 'updateTeaching']);

        //Suppression des données

        Route::delete(ENSEIGNANT_TEACHER, [TeacherTeachingController::class, 'deleteTeacher']);
        Route::delete(ENSEIGNEMENT_TEACHING, [TeacherTeachingController::class, 'deleteTeaching']);

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
