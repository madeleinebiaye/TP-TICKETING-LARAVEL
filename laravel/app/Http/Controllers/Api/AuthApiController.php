<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'device_name' => 'nullable|string|max:255',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user) {
            return response()->json([
                'message' => 'Identifiants invalides.',
            ], 401);
        }

        $isValid = Hash::check($credentials['password'], (string) $user->password);

        // Compatibilite legacy: mot de passe stocke en clair ou en md5.
        if (! $isValid) {
            $isLegacyPlain = hash_equals((string) $user->password, (string) $credentials['password']);
            $isLegacyMd5 = hash_equals((string) $user->password, md5((string) $credentials['password']));
            $isValid = $isLegacyPlain || $isLegacyMd5;

            if ($isValid) {
                $user->password = Hash::make($credentials['password']);
                $user->save();
            }
        }

        if (! $isValid) {
            return response()->json([
                'message' => 'Identifiants invalides.',
            ], 401);
        }

        $token = $user->createToken($credentials['device_name'] ?? 'api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Deconnexion effectuee.',
        ]);
    }
}
