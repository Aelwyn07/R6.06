<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Week;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Slot;

class CalendarController extends Controller
{

    private const ERROR_MESSAGE = 'Une erreur est survenue';

    /**
     * Crée un nouveau slot dans l'emploi du temps
     */
    public function storeSlot(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'duration' => 'required|numeric|min:0',
                'teacher_id' => 'required|exists:teachers,id',
                'teaching_id' => 'required|exists:teachings,id',
                'substitute_teacher_id' => 'nullable|exists:teachers,id',
                'academic_promotion_id' => 'nullable|exists:academic_promotions,id',
                'academic_group_id' => 'nullable|exists:academic_groups,id',
                'academic_subgroup_id' => 'nullable|exists:academic_subgroups,id',
                'is_neutralized' => 'boolean',
                'week_id' => 'required|exists:weeks,id',
                'type' => 'required|in:CM,TD,TP'
            ]);
    
            $errors = [];
    
            if ($validator->fails()) {
                $errors['validation'] = $validator->errors();
            } else {
                // Vérifier que l'enseignant n'a pas déjà un cours à ce moment
                $existingSlot = Slot::where('week_id', $request->week_id)
                    ->where(function ($query) use ($request) {
                        $query->where('teacher_id', $request->teacher_id)
                            ->orWhere('substitute_teacher_id', $request->teacher_id);
                    })
                    ->first();
    
                if ($existingSlot) {
                    $errors['conflict'] = "L'enseignant a déjà un cours prévu à ce moment";
                }
            }
    
            if (!empty($errors)) {
                return response()->json([
                    'error' => 'Données invalides',
                    'messages' => $errors
                ], 422);
            }
    
            // Création du slot après validation
            $slot = Slot::create($request->all());
    
            // Charger les relations pour la réponse
            $slot->load(['teacher', 'substituteTeacher', 'teaching', 'academicPromotion']);
    
            return response()->json([
                'message' => 'Slot créé avec succès',
                'slot' => $slot
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => self::ERROR_MESSAGE,
                'message' => $e->getMessage()
            ];
            $status = 500;)
        }
    }    



    public function getCalendarData($year_id): JsonResponse
    {
        try {
            // Récupérer les semaines avec leurs créneaux
            $weeks = Week::where('year_id', $year_id)
                        ->with([
                            'slots.teacher',
                            'slots.teaching',
                            'slots.substituteTeacher',
                            'slots.academicPromotion.academicGroups.academicSubgroups',
                            'slots.academicGroup',
                            'slots.academicSubgroup'
                        ])
                        ->orderBy('week_number')
                        ->get();
            $firstSlot = $weeks->pluck('slots')->flatten()->first();
            $promotion = $firstSlot->academicPromotion;
            if ($weeks->isEmpty() || !$firstSlot || !$promotion) {
                return response()->json([]);
            }

            $calendarData = $weeks->map(function ($week) use ($promotion) {
                return [
                    'week' => $week->week_number,
                    'groups' => $this->formatPromotionGroups($week->slots, $promotion)
                ];
            });

            return response()->json($calendarData);

        } catch (\Exception $e) {
            return response()->json([
                'error' => self::ERROR_MESSAGE,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function formatPromotionGroups($slots, $promotion)
    {
        return collect([
            [
                'contents' => $this->formatSlotContents($slots->where('academic_promotion_id', $promotion->id)->where('type', 'CM')),
                'groups' => $this->formatGroups($slots->where('academic_promotion_id', $promotion->id), $promotion->academicGroups)
            ]
        ]);
    }

    private function formatGroups($promotionSlots, $groups)
    {
        return $groups->map(function ($group) use ($promotionSlots) {
            $groupSlots = $promotionSlots->where('academic_group_id', $group->id);

            return [
                'contents' => $this->formatSlotContents($groupSlots->where('type', 'TD')),
                'groups' => $this->formatSubgroups($groupSlots, $group->academicSubgroups)
            ];
        })->values();
    }

    private function formatSubgroups($groupSlots, $subgroups)
    {
        return $subgroups->map(function ($subgroup) use ($groupSlots) {
            $subgroupSlots = $groupSlots->where('academic_subgroup_id', $subgroup->id);

            return [
                'contents' => $this->formatSlotContents($subgroupSlots->where('type', 'TP'))
            ];
        })->values();
    }

    private function formatSlotContents($slots)
    {
        return $slots->map(function ($slot) {
            $data = [
                'hours' => $slot->duration,
                'type' => $slot->type,
                'teacherId' => $slot->teacher ? $slot->teacher->id : null,
                'teacherCode' => $slot->teacher ? $slot->teacher->acronym : null,
                'substituteId' => $slot->substituteTeacher ? $slot->substituteTeacher->id : null,
                'isNeutralized' => $slot->is_neutralized
            ];

            // Ajouter les IDs en fonction du type de slot
            switch($slot->type) {
                case 'CM':
                    $data['promotionId'] = $slot->academic_promotion_id;
                    break;
                case 'TD':
                    $data['groupId'] = $slot->academic_group_id;
                    break;
                case 'TP':
                    $data['subgroupId'] = $slot->academic_subgroup_id;
                    break;
                default:
                    $data['error'] = "Type inconnu";
                    break;
            }
            return $data;
        })->values()->all();
    }
}
