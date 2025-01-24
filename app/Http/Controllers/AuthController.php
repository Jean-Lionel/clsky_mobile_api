<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', new Enum(UserRole::class)]
        ]);

        $user = User::create([
            'name' => $request->firstName . ' '. $request->lastName,
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ], 201);
    }
   

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->username)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations de connexion sont incorrectes.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Déconnecté avec succès']);
    }


    public function users(){
        return User::all();
    }

    public function  destroy($id){
        $user = User::find($id);

        if(auth()->user()->id === $user->id){
            throw new \Exception("Error de suppression vous ne pouvez supprimer votre propre compte ");
        }
        //
        $user->delete();
        return response()->json([
            "message" => "Suppression réussi "
        ]);
    }

    public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:8|confirmed',
    ]);

    $user = auth()->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'message' => 'Le mot de passe actuel est incorrect'
        ], 422);
    }

    $user->update([
        'password' => Hash::make($request->new_password)
    ]);

    return response()->json([
        'message' => 'Mot de passe modifié avec succès'
    ]);
}
}