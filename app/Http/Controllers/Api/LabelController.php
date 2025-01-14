<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Label;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LabelController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Label::all());
    }

    public function getLabel($label_id): JsonResponse
    {
        try {
            $label = Label::findOrFail($label_id);
            return response()->json($label);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function updateLabel(Request $request, $label_id): JsonResponse
    {
        try {
            // Validation de la requête
            $request->validate([
                'name' => 'required|string|max:255'
            ]);

            // Recherche du label
            $label = Label::findOrFail($label_id);
            
            // Mise à jour du nom
            $label->update([
                'name' => $request->name
            ]);

            return response()->json([
                'message' => 'Label modifié avec succès',
                'label' => [
                    'id' => $label->id,
                    'original_name' => $label->original_name,
                    'name' => $label->name
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Une erreur est survenue',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
