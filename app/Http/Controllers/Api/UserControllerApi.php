<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class UserControllerApi extends Controller
{
    const REQUIRED_STRING_MAX_255 = 'required|string|max:255';

    /**
     * Ajouter un nouvel utilisateur
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users',
                'firstname' => self::REQUIRED_STRING_MAX_255,
                'lastname' => self::REQUIRED_STRING_MAX_255,
                'email' => 'required|string|email|max:255|unique:users',
                'role_id' => 'required|exists:roles,id',
            ]);

            $user = User::create([
                'username' => $validated['username'],
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'role_id' => $validated['role_id'],
                'password' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'user' => $user
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation des données',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Modifier un utilisateur
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|unique:users,username,' . $id,
                'firstname' => self::REQUIRED_STRING_MAX_255,
                'lastname' => self::REQUIRED_STRING_MAX_255,
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
                'role_id' => 'required|exists:roles,id',
            ]);

            $user = User::findOrFail($id);
            $user->update([
                'username' => $validated['username'],
                'firstname' => $validated['firstname'],
                'lastname' => $validated['lastname'],
                'email' => $validated['email'],
                'role_id' => $validated['role_id'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès',
                'user' => $user
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation des données',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Récupérer tous les utilisateurs
     */
    public function index()
    {
        try {
            $users = User::with('role')
                ->select('id', 'username', 'firstname', 'lastname', 'email', 'role_id', 'password')
                ->get()
                ->map(function ($user) {
                    $user->has_password = !is_null($user->password);
                    unset($user->password);
                    return $user;
                });
            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des utilisateurs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'utilisateur',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createOrResetPassword(User $user)
    {
        // Générer un mot de passe aléatoire
        $newPassword = Str::random(4);

        // Mettre à jour le mot de passe de l'utilisateur
        $user->password = Hash::make($newPassword);
        $user->save();

        // Envoyer le mot de passe par email
        Mail::send('emails.password-reset', ['password' => $newPassword], function($message) use ($user) {
            $message->to($user->email)
                   ->subject('Votre nouveau mot de passe');
        });

        return response()->json(['message' => 'Nouveau mot de passe envoyé par email']);
    }
}
