<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function index() {
        return User::all(); // Récupérer tous les utilisateurs
    }

    public function destroy(User $user) {
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé']);
    }

    public function suspend(User $user) {
        $user->update(['role' => 'suspended']);
        return response()->json(['message' => 'Utilisateur suspendu']);
    }

    public function assignPrestataire(User $user) {
        $user->update(['role' => 'prestataire']);
        return response()->json(['message' => 'Utilisateur assigné en tant que prestataire']);
    }

      // Réactiver un utilisateur suspendu
      public function reactivateUser($id)
      {
          $user = User::findOrFail($id);

          if ($user->role === 'suspended') {
              $user->role = 'client'; // Remettre à "client" ou "prestataire" selon la logique
              $user->save();
              return response()->json(['message' => 'Utilisateur réactivé avec succès.']);
          }

          return response()->json(['message' => 'L\'utilisateur est déjà actif.'], 400);
      }

      public function removeProviderRole($id)
{
    $user = User::findOrFail($id);

    if ($user->role === 'prestataire') {
        $user->role = 'client';
        $user->save();
        return response()->json(['message' => 'Le rôle de prestataire a été retiré, l\'utilisateur est maintenant un client.']);
    }

    return response()->json(['message' => 'L\'utilisateur n\'est pas un prestataire.'], 400);
}

public function getPrestataires()
{
    // Récupérer les utilisateurs ayant le rôle "prestataire"
    $prestataires = User::where('role', 'prestataire')->get();

    return response()->json($prestataires);
}
public function getProfile($id)
{
    // Trouver le prestataire par son ID
    $prestataire = User::findOrFail($id);

    return response()->json($prestataire);
}

public function updateProfile(Request $request, $id)
{
    $prestataire = User::findOrFail($id);

    // Valider les champs
    $request->validate([
        'name' => 'string|max:255',
        'email' => 'email|max:255|unique:users,email,' . $prestataire->id,
        'phone_number' => 'nullable|string|max:20',
    ]);

    // Mettre à jour le profil
    $prestataire->update($request->only('name', 'email', 'phone_number'));

    return response()->json(['message' => 'Profil mis à jour', 'user' => $prestataire]);
}

}
