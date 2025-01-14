<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicPromotion;
use App\Models\Year;
use App\Models\AcademicGroup;
use App\Models\AcademicSubgroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    public function index($year): JsonResponse
    {
        try {
            // Vérifie si l'année existe
            $yearExists = Year::find($year);
            if (!$yearExists) {
                return response()->json([
                    'error' => 'Année non trouvée'
                ], 404);
            }

            $promotions = AcademicPromotion::with(['academicGroups.academicSubgroups'])
                ->where('year_id', $year)
                ->get()
                ->map(function ($promotion) {
                    return [
                        'id' => $promotion->id,
                        'name' => $promotion->name,
                        'groups' => $promotion->academicGroups->map(function ($group) {
                            return [
                                'id' => $group->id,
                                'name' => $group->name,
                                'subgroups' => $group->academicSubgroups->map(function ($subgroup) {
                                    return [
                                        'id' => $subgroup->id,
                                        'name' => $subgroup->name,
                                    ];
                                })
                            ];
                        })
                    ];
                });

            return response()->json($promotions);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getByPromotion($academic_promotion): JsonResponse
    {
        try {
            $promotion = AcademicPromotion::with(['academicGroups.academicSubgroups'])
                ->find($academic_promotion);

            if (!$promotion) {
                return response()->json([
                    'error' => 'Promotion non trouvée'
                ], 404);
            }

            return response()->json([
                'id' => $promotion->id,
                'name' => $promotion->name,
                'groups' => $promotion->academicGroups->map(function ($group) {
                    return [
                        'id' => $group->id,
                        'name' => $group->name,
                        'subgroups' => $group->academicSubgroups->map(function ($subgroup) {
                            return [
                                'id' => $subgroup->id,
                                'name' => $subgroup->name,
                            ];
                        })
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getByGroup($group): JsonResponse
    {
        try {
            $group = AcademicGroup::with(['academicSubgroups', 'academicPromotion'])
                ->find($group);

            if (!$group) {
                return response()->json([
                    'error' => 'Groupe non trouvé'
                ], 404);
            }

            return response()->json([
                'id' => $group->id,
                'name' => $group->name,
                'promotion' => [
                    'id' => $group->academicPromotion->id,
                    'name' => $group->academicPromotion->name,
                ],
                'subgroups' => $group->academicSubgroups->map(function ($subgroup) {
                    return [
                        'id' => $subgroup->id,
                        'name' => $subgroup->name,
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getBySubgroup($subgroup): JsonResponse
    {
        try {
            $subgroup = AcademicSubgroup::with(['academicGroup.academicPromotion'])
                ->find($subgroup);

            if (!$subgroup) {
                return response()->json([
                    'error' => 'Sous-groupe non trouvé'
                ], 404);
            }

            return response()->json([
                'id' => $subgroup->id,
                'name' => $subgroup->name,
                'group' => [
                    'id' => $subgroup->academicGroup->id,
                    'name' => $subgroup->academicGroup->name,
                    'promotion' => [
                        'id' => $subgroup->academicGroup->academicPromotion->id,
                        'name' => $subgroup->academicGroup->academicPromotion->name,
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storePromotion(Request $request, $year): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Vérifie si l'année existe
            $yearExists = Year::find($year);
            if (!$yearExists) {
                return response()->json([
                    'error' => 'Année non trouvée'
                ], 404);
            }

            // Vérifie si une promotion avec le même nom existe déjà pour cette année
            $existingPromotion = AcademicPromotion::where('name', $request->name)
                ->where('year_id', $year)
                ->first();

            if ($existingPromotion) {
                return response()->json([
                    'error' => 'Une promotion avec ce nom existe déjà pour cette année'
                ], 422);
            }

            $promotion = AcademicPromotion::create([
                'name' => $request->name,
                'year_id' => $year
            ]);

            return response()->json([
                'message' => 'Promotion créée avec succès',
                'promotion' => [
                    'id' => $promotion->id,
                    'name' => $promotion->name,
                    'year_id' => $promotion->year_id
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeGroup(Request $request, $promotion): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Vérifie si la promotion existe
            $promotionExists = AcademicPromotion::find($promotion);
            if (!$promotionExists) {
                return response()->json([
                    'error' => 'Promotion non trouvée'
                ], 404);
            }

            // Vérifie si un groupe avec le même nom existe déjà pour cette promotion
            $existingGroup = AcademicGroup::where('name', $request->name)
                ->where('academic_promotion_id', $promotion)
                ->first();

            if ($existingGroup) {
                return response()->json([
                    'error' => 'Un groupe avec ce nom existe déjà pour cette promotion'
                ], 422);
            }

            $group = AcademicGroup::create([
                'name' => $request->name,
                'academic_promotion_id' => $promotion
            ]);

            return response()->json([
                'message' => 'Groupe créé avec succès',
                'group' => [
                    'id' => $group->id,
                    'name' => $group->name,
                    'academic_promotion_id' => $group->academic_promotion_id
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeSubgroup(Request $request, $group): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            $groupExists = AcademicGroup::find($group);
            if (!$groupExists) {
                return response()->json([
                    'error' => 'Groupe non trouvé'
                ], 404);
            }

            $existingSubgroup = AcademicSubgroup::where('name', $request->name)
                ->where('academic_group_id', $group)
                ->first();

            if ($existingSubgroup) {
                return response()->json([
                    'error' => 'Un sous-groupe avec ce nom existe déjà pour ce groupe'
                ], 422);
            }

            $subgroup = AcademicSubgroup::create([
                'name' => $request->name,
                'academic_group_id' => $group
            ]);

            return response()->json([
                'message' => 'Sous-groupe créé avec succès',
                'subgroup' => [
                    'id' => $subgroup->id,
                    'name' => $subgroup->name,
                    'academic_group_id' => $subgroup->academic_group_id
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePromotion(Request $request, $promotion): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Vérifie si la promotion existe
            $promotionToUpdate = AcademicPromotion::find($promotion);
            if (!$promotionToUpdate) {
                return response()->json([
                    'error' => 'Promotion non trouvée'
                ], 404);
            }

            // Vérifie si une autre promotion avec le même nom existe déjà pour cette année
            $existingPromotion = AcademicPromotion::where('name', $request->name)
                ->where('year_id', $promotionToUpdate->year_id)
                ->where('id', '!=', $promotion)
                ->first();

            if ($existingPromotion) {
                return response()->json([
                    'error' => 'Une promotion avec ce nom existe déjà pour cette année'
                ], 422);
            }

            $promotionToUpdate->update([
                'name' => $request->name
            ]);

            return response()->json([
                'message' => 'Promotion modifiée avec succès',
                'promotion' => [
                    'id' => $promotionToUpdate->id,
                    'name' => $promotionToUpdate->name,
                    'year_id' => $promotionToUpdate->year_id
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateGroup(Request $request, $group): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Vérifie si le groupe existe
            $groupToUpdate = AcademicGroup::find($group);
            if (!$groupToUpdate) {
                return response()->json([
                    'error' => 'Groupe non trouvé'
                ], 404);
            }

            // Vérifie si un autre groupe avec le même nom existe déjà pour cette promotion
            $existingGroup = AcademicGroup::where('name', $request->name)
                ->where('academic_promotion_id', $groupToUpdate->academic_promotion_id)
                ->where('id', '!=', $group)
                ->first();

            if ($existingGroup) {
                return response()->json([
                    'error' => 'Un groupe avec ce nom existe déjà pour cette promotion'
                ], 422);
            }

            $groupToUpdate->update([
                'name' => $request->name
            ]);

            return response()->json([
                'message' => 'Groupe modifié avec succès',
                'group' => [
                    'id' => $groupToUpdate->id,
                    'name' => $groupToUpdate->name,
                    'academic_promotion_id' => $groupToUpdate->academic_promotion_id
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateSubgroup(Request $request, $subgroup): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Vérifie si le sous-groupe existe
            $subgroupToUpdate = AcademicSubgroup::find($subgroup);
            if (!$subgroupToUpdate) {
                return response()->json([
                    'error' => 'Sous-groupe non trouvé'
                ], 404);
            }

            // Vérifie si un autre sous-groupe avec le même nom existe déjà pour ce groupe
            $existingSubgroup = AcademicSubgroup::where('name', $request->name)
                ->where('academic_group_id', $subgroupToUpdate->academic_group_id)
                ->where('id', '!=', $subgroup)
                ->first();

            if ($existingSubgroup) {
                return response()->json([
                    'error' => 'Un sous-groupe avec ce nom existe déjà pour ce groupe'
                ], 422);
            }

            $subgroupToUpdate->update([
                'name' => $request->name
            ]);

            return response()->json([
                'message' => 'Sous-groupe modifié avec succès',
                'subgroup' => [
                    'id' => $subgroupToUpdate->id,
                    'name' => $subgroupToUpdate->name,
                    'academic_group_id' => $subgroupToUpdate->academic_group_id
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deletePromotion($promotion): JsonResponse
    {
        try {
            $promotionToDelete = AcademicPromotion::with(['academicGroups.academicSubgroups'])
                ->find($promotion);
            
            if (!$promotionToDelete) {
                return response()->json([
                    'error' => 'Promotion non trouvée'
                ], 404);
            }

            // Sauvegarde des données avant suppression
            $deletedPromotion = [
                'message' => 'Promotion supprimée avec succès',
                'promotion' => [
                    'id' => $promotionToDelete->id,
                    'name' => $promotionToDelete->name,
                    'year_id' => $promotionToDelete->year_id,
                    'groups' => $promotionToDelete->academicGroups->map(function ($group) {
                        return [
                            'id' => $group->id,
                            'name' => $group->name,
                            'subgroups' => $group->academicSubgroups->map(function ($subgroup) {
                                return [
                                    'id' => $subgroup->id,
                                    'name' => $subgroup->name
                                ];
                            })
                        ];
                    })
                ]
            ];

            // La suppression en cascade est gérée par la base de données
            $promotionToDelete->delete();

            return response()->json($deletedPromotion);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteGroup($group): JsonResponse
    {
        try {
            $groupToDelete = AcademicGroup::with(['academicSubgroups', 'academicPromotion', 'slots'])
                ->find($group);
            
            if (!$groupToDelete) {
                return response()->json([
                    'error' => 'Groupe non trouvé'
                ], 404);
            }

            // Sauvegarde des données avant suppression
            $deletedGroup = [
                'message' => 'Groupe supprimé avec succès',
                'group' => [
                    'id' => $groupToDelete->id,
                    'name' => $groupToDelete->name,
                    'promotion' => [
                        'id' => $groupToDelete->academicPromotion->id,
                        'name' => $groupToDelete->academicPromotion->name,
                    ],
                    'subgroups' => $groupToDelete->academicSubgroups->map(function ($subgroup) {
                        return [
                            'id' => $subgroup->id,
                            'name' => $subgroup->name,
                        ];
                    })
                ]
            ];

            DB::beginTransaction();
            try {
                // Delete associated slots first
                $groupToDelete->slots()->delete();
                // Then delete the group (which will cascade to subgroups)
                $groupToDelete->delete();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            return response()->json($deletedGroup);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue lors de la suppression',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteSubgroup($subgroup): JsonResponse
    {
        try {
            $subgroupToDelete = AcademicSubgroup::with(['academicGroup.academicPromotion'])
                ->find($subgroup);
            
            if (!$subgroupToDelete) {
                return response()->json([
                    'error' => 'Sous-groupe non trouvé'
                ], 404);
            }

            // Sauvegarde des données avant suppression
            $deletedSubgroup = [
                'message' => 'Sous-groupe supprimé avec succès',
                'subgroup' => [
                    'id' => $subgroupToDelete->id,
                    'name' => $subgroupToDelete->name,
                    'group' => [
                        'id' => $subgroupToDelete->academicGroup->id,
                        'name' => $subgroupToDelete->academicGroup->name,
                        'promotion' => [
                            'id' => $subgroupToDelete->academicGroup->academicPromotion->id,
                            'name' => $subgroupToDelete->academicGroup->academicPromotion->name,
                        ]
                    ]
                ]
            ];

            $subgroupToDelete->delete();

            return response()->json($deletedSubgroup);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
