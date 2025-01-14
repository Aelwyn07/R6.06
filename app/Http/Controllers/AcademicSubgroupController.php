<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use App\Models\AcademicSubgroup;

class AcademicSubgroupController extends Controller
{
    public function index()
    {
        $subgroups = AcademicSubgroup::with(['academicGroup.academicPromotion'])->get();

        if (request()->wantsJson()) {
            return new JsonResponse($subgroups);
        }

        return Inertia::render('GroupPage', [
            'subgroups' => $subgroups
        ]);

    }
}
