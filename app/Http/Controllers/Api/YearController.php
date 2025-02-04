<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Year;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class YearController extends Controller
{
    const ERROR = 'Une erreur est survenue';

    public function index(): JsonResponse
    {
        try {
            $years = Year::with(['semesters', 'trimesters'])
                ->get()
                ->map(function ($year) {
                    return [
                        'id' => $year->id,
                        'name' => $year->name,
                        'periodicity' => $year->periodicity,
                        'periods' => $year->periodicity === 'Semestrial'
                            ? $year->semesters->map(fn($s) => ['id' => $s->id, 'number' => $s->semester_number])
                            : $year->trimesters->map(fn($t) => ['id' => $t->id, 'number' => $t->trimester_number])
                    ];
                });

            return response()->json($years);

        } catch (\Exception $e) {
            return response()->json([
                'error' => self::ERROR,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:years,name',
                'periodicity' => 'required|in:Semestrial,Trimestrial'
            ]);

            $year = Year::create([
                'name' => $request->name,
                'periodicity' => $request->periodicity
            ]);

            return response()->json([
                'message' => 'Année créée avec succès',
                'year' => [
                    'id' => $year->id,
                    'name' => $year->name,
                    'periodicity' => $year->periodicity
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => self::ERROR,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $year = Year::with(['semesters', 'trimesters', 'teachings', 'teachers', 'academicPromotions'])
                ->find($id);

            if (!$year) {
                return response()->json([
                    'error' => 'Année non trouvée'
                ], 404);
            }

            return response()->json([
                'id' => $year->id,
                'name' => $year->name,
                'periodicity' => $year->periodicity,
                'periods' => $year->periodicity === 'Semestrial'
                    ? $year->semesters->map(fn($s) => ['id' => $s->id, 'number' => $s->semester_number])
                    : $year->trimesters->map(fn($t) => ['id' => $t->id, 'number' => $t->trimester_number]),
                'teachings_count' => $year->teachings->count(),
                'teachers_count' => $year->teachers->count(),
                'promotions_count' => $year->academicPromotions->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => self::ERROR,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
