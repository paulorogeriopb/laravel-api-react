<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Mail\SendEmailForgotPasswordCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon as Carbono;

class RecoverPasswordCodeController extends Controller
{



    public function forgotPasswordCode(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            Log::warning("Tentativa de redefinição de senha", ['email' => $request->email]);
            return response()->json([
                'status' => false,
                'message' => 'Não foi possível redefinir a senha, tente novamente mais tarde',
            ], 401);
        }

        try {
            $code = null;

            DB::transaction(function () use ($request, $user, &$code) {
                DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->delete();

                $code = mt_rand(100000, 999999);
                $token = Hash::make($request->email . $code);

                DB::table('password_reset_tokens')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => now()
                ]);
            });

            $expiration = Carbono::now()->addHour();
            Mail::to($user->email)->send(new SendEmailForgotPasswordCode(
                $user,
                $code,
                $expiration->format('d/m/Y'),
                $expiration->format('H:i:s')
            ));

            Log::info("Código de recuperação enviado", ['email' => $request->email]);

            return response()->json([
                'status' => true,
                'message' => 'Enviado e-mail com código de redefinição de senha! Verifique seu e-mail.',
            ], 200);

        } catch (\Throwable $th) {
            Log::error("Erro ao recuperar senha", [
                'email' => $request->email,
                'error' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Não foi possível redefinir a senha, tente novamente mais tarde',
            ], 500);
        }
    }




}
