<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $user = Auth::user();
                $token =  $request->user()->createToken('api-token')->plainTextToken;

                Log::info("Login realizado com sucesso", ['action_user_id' => $user->id]);

                return response()->json([
                    'status' => true,
                    'token' => $token,
                    'user' => $user,
                ], 201);
            } else {
                Log::notice("Login inválido", ['email' => $request->email]);
                return response()->json([
                    'status' => false,
                    'message' => 'Login ou senha inválidos',
                ], 401);
            }
        } catch (\Throwable $th) {
            Log::warning("Erro ao validar login", [
                'email' => $request->email,
                'error' => $th->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Erro ao realizar login',
            ], 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $authUserId = Auth::check() ? Auth::id() : null;

            if (!$authUserId) {
                Log::notice("Tentativa de logout sem autenticação");

                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }

            $user = User::find($authUserId);

            $user?->tokens()?->delete();

            Log::info("Logout realizado com sucesso", ['action_user_id' => $authUserId]);

            return response()->json([
                'status' => true,
                'message' => 'Logout realizado com sucesso.',
            ]);
        } catch (\Throwable $th) {
            Log::warning("Erro ao realizar logout", ['error' => $th->getMessage()]);

            return response()->json([
                'status' => false,
                'message' => 'Erro ao realizar logout',
            ], 500);
        }
    }
}
