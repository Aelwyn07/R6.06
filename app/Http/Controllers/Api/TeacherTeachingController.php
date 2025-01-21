<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Year;
use App\Models\Teaching;
use App\Models\Semester;
use App\Models\Trimester;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherTeachingController extends Controller
{
    public function getTeachers($year): JsonResponse
    {
        try {
            // Vérifie si l'année existe
            $yearExists = Year::find($year);
            if (!$yearExists) {
                return response()->json([
                    'error' => 'Année non trouvée'
                ], 404);
            }

            // Récupère les enseignants avec leurs enseignements pour l'année spécifiée
            $teachers = Teacher::with(['teachings'])
                ->where('year_id', $year)
                ->get()
                ->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'acronym' => $teacher->acronym,
                        'first_name' => $teacher->first_name,
                        'last_name' => $teacher->last_name,
                        'teachings' => $teacher->teachings->map(function ($teaching) {
                            return [
                                'id' => $teaching->id,
                                'title' => $teaching->title,
                                'apogee_code' => $teaching->apogee_code
                            ];
                        })
                    ];
                });

            return response()->json($teachers);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTeachings($year): JsonResponse
    {
        try {
            // Vérifie si l'année existe
            $yearExists = Year::find($year);
            if (!$yearExists) {
                return response()->json([
                    'error' => 'Année non trouvée'
                ], 404);
            }

            // Récupère les enseignements pour l'année spécifiée
            $teachings = Teaching::where('year_id', $year)
                ->get()
                ->map(function ($teaching) {
                    return [
                        'id' => $teaching->id,
                        'title' => $teaching->title,
                        'apogee_code' => $teaching->apogee_code,
                        'tp_hours_initial' => $teaching->tp_hours_initial,
                        'tp_hours_continued' => $teaching->tp_hours_continued,
                        'td_hours_intial' => $teaching->td_hours_intial,
                        'td_hours_continued' => $teaching->td_hours_continued,
                        'cm_hours_initial' => $teaching->cm_hours_initial,
                        'cm_hours_continued' => $teaching->cm_hours_continued,
                        'semester' => $teaching->semester?->semester_number,
                        'trimester' => $teaching->trimester?->trimester_number,
                        'year_id' => $teaching->year_id,
                    ];
                });

            return response()->json($teachings);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTeachersByTeaching($teaching_id): JsonResponse
    {
        try {
            // Vérifie si l'enseignement existe
            $teaching = Teaching::find($teaching_id);
            if (!$teaching) {
                return response()->json([
                    'error' => 'Enseignement non trouvé'
                ], 404);
            }

            // Récupère les enseignants assignés à cet enseignement
            $teachers = $teaching->teachers->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'acronym' => $teacher->acronym,
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name
                ];
            });

            return response()->json($teachers);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTeachingsByTeacher($teacher_id): JsonResponse
    {
        try {
            // Vérifie si l'enseignant existe
            $teacher = Teacher::find($teacher_id);
            if (!$teacher) {
                return response()->json([
                    'error' => 'Enseignant non trouvé'
                ], 404);
            }

            // Récupère les enseignements assignés à cet enseignant
            $teachings = $teacher->teachings->map(function ($teaching) {
                return [
                    'id' => $teaching->id,
                    'title' => $teaching->title,
                    'apogee_code' => $teaching->apogee_code,                
                ];
            });

            return response()->json($teachings);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTeacher($teacher_id): JsonResponse
    {
        try {
            // Vérifie si l'enseignant existe
            $teacher = Teacher::with(['teachings', 'year'])
                ->find($teacher_id);

            if (!$teacher) {
                return response()->json([
                    'error' => 'Enseignant non trouvé'
                ], 404);
            }

            // Prépare la réponse avec les données de l'enseignant
            $response = [
                'id' => $teacher->id,
                'acronym' => $teacher->acronym,
                'first_name' => $teacher->first_name,
                'last_name' => $teacher->last_name,
                'year' => [
                    'id' => $teacher->year->id,
                    'name' => $teacher->year->name
                ],
                'teachings' => $teacher->teachings->map(function ($teaching) {
                    return [
                        'id' => $teaching->id,
                        'title' => $teaching->title,
                        'apogee_code' => $teaching->apogee_code
                    ];
                })
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTeaching($teaching_id): JsonResponse
    {
        try {
            // Vérifie si l'enseignement existe
            $teaching = Teaching::with(['teachers', 'year'])
                ->find($teaching_id);

            if (!$teaching) {
                return response()->json([
                    'error' => 'Enseignement non trouvé'
                ], 404);
            }

            // Prépare la réponse avec les données de l'enseignement
            $response = [
                'id' => $teaching->id,
                'title' => $teaching->title,
                'apogee_code' => $teaching->apogee_code,
                'tp_hours_initial' => $teaching->tp_hours_initial,
                'tp_hours_continued' => $teaching->tp_hours_continued,
                'td_hours_initial' => $teaching->td_hours_initial,
                'td_hours_continued' => $teaching->td_hours_continued,
                'cm_hours_initial' => $teaching->cm_hours_initial,
                'cm_hours_continued' => $teaching->cm_hours_continued,
                'semester' => $teaching->semester?->semester_number,
                'trimester' => $teaching->trimester?->trimester_number,
                'year' => [
                    'id' => $teaching->year->id,
                    'name' => $teaching->year->name
                ],
                'teachers' => $teaching->teachers->map(function ($teacher) {
                    return [
                        'id' => $teacher->id,
                        'acronym' => $teacher->acronym,
                        'first_name' => $teacher->first_name,
                        'last_name' => $teacher->last_name
                    ];
                })
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeTeacher(Request $request, $year): JsonResponse
    {
        try {
            $request->validate([
                'acronym' => 'required|string|max:10',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'user_id' => 'required|exists:users,id'
            ]);

            // Vérifie si l'année existe
            $yearExists = Year::find($year);
            if (!$yearExists) {
                return response()->json([
                    'error' => 'Année non trouvée'
                ], 404);
            }

            // Vérifie si un enseignant avec le même acronyme existe déjà pour cette année
            $existingTeacher = Teacher::where('acronym', $request->acronym)
                ->where('year_id', $year)
                ->first();

            if ($existingTeacher) {
                return response()->json([
                    'error' => 'Un enseignant avec cet acronyme existe déjà pour cette année'
                ], 422);
            }

            $teacher = Teacher::create([
                'acronym' => $request->acronym,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'user_id' => $request->user_id,
                'year_id' => $year
            ]);

            return response()->json([
                'message' => 'Enseignant créé avec succès',
                'teacher' => [
                    'id' => $teacher->id,
                    'acronym' => $teacher->acronym,
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name,
                    'user_id' => $teacher->user_id,
                    'year_id' => $teacher->year_id
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeTeaching(Request $request, $year): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'apogee_code' => 'required|string|max:50',
                'tp_hours_initial' => 'nullable|numeric|min:0',
                'tp_hours_continued' => 'nullable|numeric|min:0',
                'td_hours_initial' => 'nullable|numeric|min:0',
                'td_hours_continued' => 'nullable|numeric|min:0',
                'cm_hours_initial' => 'nullable|numeric|min:0',
                'cm_hours_continued' => 'nullable|numeric|min:0',
                'semester' => 'nullable|integer|min:1|max:6',
                'trimester' => 'nullable|integer|min:1|max:4'
            ]);

            // Vérifie qu'un seul des deux est spécifié
            if ($request->semester && $request->trimester) {
                return response()->json([
                    'error' => 'Un enseignement ne peut pas être lié à la fois à un semestre et à un trimestre'
                ], 422);
            }

            if (!$request->semester && !$request->trimester) {
                return response()->json([
                    'error' => 'Un enseignement doit être lié soit à un semestre, soit à un trimestre'
                ], 422);
            }

            // Vérifie si l'année existe
            $yearExists = Year::find($year);
            if (!$yearExists) {
                return response()->json([
                    'error' => 'Année non trouvée'
                ], 404);
            }

            // Vérifie si un enseignement avec le même code apogée existe déjà pour cette année
            $existingTeaching = Teaching::where('apogee_code', $request->apogee_code)
                ->where('year_id', $year)
                ->first();

            if ($existingTeaching) {
                return response()->json([
                    'error' => 'Un enseignement avec ce code apogée existe déjà pour cette année'
                ], 422);
            }

            // Trouve le semestre ou le trimestre correspondant
            $semester_id = null;
            $trimester_id = null;

            if ($request->semester) {
                $semester = Semester::where('semester_number', $request->semester)
                    ->where('year_id', $year)
                    ->first();
                
                if (!$semester) {
                    return response()->json([
                        'error' => 'Le semestre spécifié n\'existe pas pour cette année'
                    ], 422);
                }
                
                $semester_id = $semester->id;
            } else {
                $trimester = Trimester::where('trimester_number', $request->trimester)
                    ->where('year_id', $year)
                    ->first();
                
                if (!$trimester) {
                    return response()->json([
                        'error' => 'Le trimestre spécifié n\'existe pas pour cette année'
                    ], 422);
                }
                
                $trimester_id = $trimester->id;
            }

            $teaching = Teaching::create([
                'title' => $request->title,
                'apogee_code' => $request->apogee_code,
                'tp_hours_initial' => $request->tp_hours_initial,
                'tp_hours_continued' => $request->tp_hours_continued,
                'td_hours_initial' => $request->td_hours_initial,
                'td_hours_continued' => $request->td_hours_continued,
                'cm_hours_initial' => $request->cm_hours_initial,
                'cm_hours_continued' => $request->cm_hours_continued,
                'semester_id' => $semester_id,
                'trimester_id' => $trimester_id,
                'year_id' => $year
            ]);

            return response()->json([
                'message' => 'Enseignement créé avec succès',
                'teaching' => [
                    'id' => $teaching->id,
                    'title' => $teaching->title,
                    'apogee_code' => $teaching->apogee_code,
                    'tp_hours_initial' => $teaching->tp_hours_initial,
                    'tp_hours_continued' => $teaching->tp_hours_continued,
                    'td_hours_initial' => $teaching->td_hours_initial,
                    'td_hours_continued' => $teaching->td_hours_continued,
                    'cm_hours_initial' => $teaching->cm_hours_initial,
                    'cm_hours_continued' => $teaching->cm_hours_continued,
                    'semester_id' => $teaching->semester_id,
                    'trimester_id' => $teaching->trimester_id,
                    'year_id' => $teaching->year_id
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateTeacher(Request $request, $teacher_id): JsonResponse
    {
        try {
            $request->validate([
                'acronym' => 'required|string|max:10',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'user_id' => 'required|exists:users,id'
            ]);

            // Vérifie si l'enseignant existe
            $teacher = Teacher::find($teacher_id);
            if (!$teacher) {
                return response()->json([
                    'error' => 'Enseignant non trouvé'
                ], 404);
            }

            // Vérifie si un autre enseignant avec le même acronyme existe déjà pour cette année
            $existingTeacher = Teacher::where('acronym', $request->acronym)
                ->where('year_id', $teacher->year_id)
                ->where('id', '!=', $teacher_id)
                ->first();

            if ($existingTeacher) {
                return response()->json([
                    'error' => 'Un enseignant avec cet acronyme existe déjà pour cette année'
                ], 422);
            }

            $teacher->update([
                'acronym' => $request->acronym,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'user_id' => $request->user_id
            ]);

            // Charge les relations pour la réponse
            $teacher->load(['teachings', 'year']);

            return response()->json([
                'message' => 'Enseignant modifié avec succès',
                'teacher' => [
                    'id' => $teacher->id,
                    'acronym' => $teacher->acronym,
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name,
                    'year' => [
                        'id' => $teacher->year->id,
                        'name' => $teacher->year->name
                    ],
                    'teachings' => $teacher->teachings->map(function ($teaching) {
                        return [
                            'id' => $teaching->id,
                            'title' => $teaching->title,
                            'apogee_code' => $teaching->apogee_code
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateTeaching(Request $request, $teaching_id): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'apogee_code' => 'required|string|max:50',
                'tp_hours_initial' => 'nullable|numeric|min:0',
                'tp_hours_continued' => 'nullable|numeric|min:0',
                'td_hours_initial' => 'nullable|numeric|min:0',
                'td_hours_continued' => 'nullable|numeric|min:0',
                'cm_hours_initial' => 'nullable|numeric|min:0',
                'cm_hours_continued' => 'nullable|numeric|min:0',
                'semester' => 'nullable|integer|min:1|max:6',
                'trimester' => 'nullable|integer|min:1|max:4'
            ]);

            // Vérifie si l'enseignement existe
            $teaching = Teaching::with(['teachers', 'year'])->find($teaching_id);
            if (!$teaching) {
                return response()->json([
                    'error' => 'Enseignement non trouvé'
                ], 404);
            }

            // Vérifie si un autre enseignement avec le même code apogée existe déjà pour cette année
            $existingTeaching = Teaching::where('apogee_code', $request->apogee_code)
                ->where('year_id', $teaching->year_id)
                ->where('id', '!=', $teaching_id)
                ->first();

            if ($existingTeaching) {
                return response()->json([
                    'error' => 'Un enseignement avec ce code apogée existe déjà pour cette année'
                ], 422);
            }

            // Trouve le semestre ou le trimestre correspondant
            $semester_id = null;
            $trimester_id = null;

            if ($request->semester) {
                $semester = Semester::where('semester_number', $request->semester)
                    ->where('year_id', $teaching->year_id)
                    ->first();
                
                if (!$semester) {
                    return response()->json([
                        'error' => 'Le semestre spécifié n\'existe pas pour cette année'
                    ], 422);
                }
                
                $semester_id = $semester->id;
            } else {
                $trimester = Trimester::where('trimester_number', $request->trimester)
                    ->where('year_id', $teaching->year_id)
                    ->first();
                
                if (!$trimester) {
                    return response()->json([
                        'error' => 'Le trimestre spécifié n\'existe pas pour cette année'
                    ], 422);
                }
                
                $trimester_id = $trimester->id;
            }

            $teaching->update([
                'title' => $request->title,
                'apogee_code' => $request->apogee_code,
                'tp_hours_initial' => $request->tp_hours_initial,
                'tp_hours_continued' => $request->tp_hours_continued,
                'td_hours_initial' => $request->td_hours_initial,
                'td_hours_continued' => $request->td_hours_continued,
                'cm_hours_initial' => $request->cm_hours_initial,
                'cm_hours_continued' => $request->cm_hours_continued,
                'semester_id' => $semester_id,
                'trimester_id' => $trimester_id,
            ]);

            // Recharge les relations pour la réponse
            $teaching->load(['teachers', 'year']);

            return response()->json([
                'message' => 'Enseignement modifié avec succès',
                'teaching' => [
                    'id' => $teaching->id,
                    'title' => $teaching->title,
                    'apogee_code' => $teaching->apogee_code,
                    'tp_hours_initial' => $teaching->tp_hours_initial,
                    'tp_hours_continued' => $teaching->tp_hours_continued,
                    'td_hours_initial' => $teaching->td_hours_initial,
                    'td_hours_continued' => $teaching->td_hours_continued,
                    'cm_hours_initial' => $teaching->cm_hours_initial,
                    'cm_hours_continued' => $teaching->cm_hours_continued,
                    'semester_id' => $teaching->semester_id,
                    'trimester_id' => $teaching->trimester_id,
                    'year' => [
                        'id' => $teaching->year->id,
                        'name' => $teaching->year->name
                    ],
                    'teachers' => $teaching->teachers->map(function ($teacher) {
                        return [
                            'id' => $teacher->id,
                            'acronym' => $teacher->acronym,
                            'first_name' => $teacher->first_name,
                            'last_name' => $teacher->last_name
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getTeacherTeaching($teacher_id, $teaching_id): JsonResponse
    {
        try {
            $teacher = Teacher::findOrFail($teacher_id);
            $teaching = Teaching::findOrFail($teaching_id);

            // Vérifie si la relation existe dans la table pivot
            $relation = DB::table('teachers_teachings')
                ->where('teacher_id', $teacher_id)
                ->where('teaching_id', $teaching_id)
                ->first();

            if (!$relation) {
                return response()->json([
                    'error' => 'Relation entre enseignant et enseignement non trouvée'
                ], 404);
            }

            return response()->json([
                'teacher' => [
                    'id' => $teacher->id,
                    'acronym' => $teacher->acronym,
                    'first_name' => $teacher->first_name,
                    'last_name' => $teacher->last_name
                ],
                'teaching' => [
                    'id' => $teaching->id,
                    'title' => $teaching->title,
                    'apogee_code' => $teaching->apogee_code,
                    'tp_hours_initial' => $teaching->tp_hours_initial,
                    'tp_hours_continued' => $teaching->tp_hours_continued,
                    'td_hours_intial' => $teaching->td_hours_intial,
                    'td_hours_continued' => $teaching->td_hours_continued,
                    'cm_hours_initial' => $teaching->cm_hours_initial,
                    'cm_hours_continued' => $teaching->cm_hours_continued
                ],
                'created_at' => $relation->created_at,
                'updated_at' => $relation->updated_at
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Enseignant ou enseignement non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeTeacherTeaching(Request $request, $teacher_id, $teaching_id): JsonResponse
    {
        try {
            // Vérifie si l'enseignant et l'enseignement existent
            $teacher = Teacher::findOrFail($teacher_id);
            $teaching = Teaching::findOrFail($teaching_id);

            // Vérifie si la relation existe déjà
            $existingRelation = DB::table('teachers_teachings')
                ->where('teacher_id', $teacher_id)
                ->where('teaching_id', $teaching_id)
                ->first();

            if ($existingRelation) {
                return response()->json([
                    'error' => 'Relation entre enseignant et enseignement existe déjà'
                ], 422);
            }

            DB::table('teachers_teachings')
                ->insert([
                    'teacher_id' => $teacher_id,
                    'teaching_id' => $teaching_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

            return response()->json([
                'message' => 'Relation enseignant-enseignement créée avec succès'
            ], 201);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Enseignant ou enseignement non trouvé'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteTeacherTeaching($teacher_id, $teaching_id): JsonResponse
    {
        try {

            $teacher = Teacher::findOrFail($teacher_id);
            $teaching = Teaching::findOrFail($teaching_id);

            // Vérifie si la relation existe dans la table pivot
            $relation = DB::table('teachers_teachings')
                ->where('teacher_id', $teacher_id)
                ->where('teaching_id', $teaching_id)
                ->first();

            if (!$relation) {
                return response()->json([
                    'error' => 'Relation entre enseignant et enseignement non trouvée'
                ], 404);
            }

            DB::table('teachers_teachings')
                ->where('teacher_id', $teacher_id)
                ->where('teaching_id', $teaching_id)
                ->delete();

            return response()->json([
                'message' => 'Relation enseignant-enseignement supprimée avec succès'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vérifie la répartition des heures des lots d'un enseignement
     */
    public function checkTeachingHours($teaching_id): JsonResponse
    {
        try {
            $teaching = Teaching::with(['slots'])->findOrFail($teaching_id);
            
            // Initialisation des compteurs
            $totalTP = 0;
            $totalTD = 0;
            $totalCM = 0;
            $groupHours = [];
            $slotDetails = [];
            
            // Calcul des heures par groupe et total
            foreach ($teaching->slots as $slot) {
                switch($slot->type) {
                    case 'TP':
                        $totalTP += $slot->duration;
                        break;
                    case 'TD':
                        $totalTD += $slot->duration;
                        break;
                    case 'CM':
                        $totalCM += $slot->duration;
                        break;
                    default:
                        "Erreur";
                    break;
                }
                
                if (!isset($groupHours[$slot->academic_promotion_id])) {
                    $groupHours[$slot->academic_promotion_id] = [
                        'total' => 0,
                        'CM' => 0,
                        'TD' => 0,
                        'TP' => 0
                    ];
                }
                $groupHours[$slot->academic_promotion_id]['total'] += $slot->duration;
                $groupHours[$slot->academic_promotion_id][$slot->type] += $slot->duration;
                
                // Ajoute les détails du slot
                $slotDetails[] = [
                    'id' => $slot->id,
                    'type' => $slot->type,
                    'duration' => $slot->duration,
                    'promotion_id' => $slot->academic_promotion_id,
                    'week_id' => $slot->week_id,
                    'teacher_id' => $slot->teacher_id,
                    'substitute_teacher_id' => $slot->substitute_teacher_id
                ];
            }

            $hoursMatch = true;
            $errors = [];
            
            // Vérifie les heures définies
            $definedHours = [
                'TP' => [
                    'initial' => $teaching->tp_hours_initial,
                    'continued' => $teaching->tp_hours_continued,
                    'total' => $teaching->tp_hours_initial + ($teaching->tp_hours_continued ?? 0),
                    'actual' => $totalTP
                ],
                'TD' => [
                    'initial' => $teaching->td_hours_initial,
                    'continued' => $teaching->td_hours_continued,
                    'total' => $teaching->td_hours_initial + ($teaching->td_hours_continued ?? 0),
                    'actual' => $totalTD
                ],
                'CM' => [
                    'initial' => $teaching->cm_hours_initial,
                    'continued' => $teaching->cm_hours_continued,
                    'total' => $teaching->cm_hours_initial + ($teaching->cm_hours_continued ?? 0),
                    'actual' => $totalCM
                ]
            ];
            
            // Vérifie chaque type d'heures
            foreach (['TP', 'TD', 'CM'] as $type) {
                if ($definedHours[$type]['actual'] !== $definedHours[$type]['total']) {
                    $hoursMatch = false;
                    $errors[] = "Le total des heures de {$type} ({$definedHours[$type]['actual']}h) ne correspond pas aux heures définies dans l'enseignement ({$definedHours[$type]['total']}h)";
                }
            }

            // Vérifie l'équité entre les groupes
            $groupsBalanced = true;
            if (count($groupHours) > 1) {
                $firstGroupHours = reset($groupHours)['total'];
                foreach ($groupHours as $groupId => $hours) {
                    if ($hours['total'] !== $firstGroupHours) {
                        $groupsBalanced = false;
                        $errors[] = "Le groupe {$groupId} a {$hours['total']}h alors que d'autres groupes ont {$firstGroupHours}h";
                    }
                }
            }

            return response()->json([
                'teaching' => [
                    'id' => $teaching->id,
                    'title' => $teaching->title,
                    'apogee_code' => $teaching->apogee_code,
                    'defined_hours' => $definedHours
                ],
                'status' => [
                    'hours_match' => $hoursMatch,
                    'groups_balanced' => $groupsBalanced,
                ],
                'totals' => [
                    'tp' => $totalTP,
                    'td' => $totalTD,
                    'cm' => $totalCM,
                    'all' => $totalTP + $totalTD + $totalCM
                ],
                'groups' => $groupHours,
                'slots' => $slotDetails,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
