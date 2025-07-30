<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UserPasswordRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        try {
            $user = User::find(Auth::id());

            if (!$user) {
                Log::notice("Perfil não encontrado", ['action_user_id' => Auth::id()]);

                return response()->json([
                    'status' => false,
                    'message' => 'Perfil não encontrado'
                ], 404);
            }

            Log::info("Visualizar Perfil", [
                'user_id' => $user->id,
                'action_user_id' => Auth::id()
            ]);

            return response()->json([
                'status' => true,
                'user' => $user
            ], 200);

        } catch (\Throwable $th) {
            Log::error("Erro ao visualizar perfil", [
                'action_user_id' => Auth::id(),
                'error' => $th->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Erro ao visualizar perfil'
            ], 400);
        }
    }

    public function update(ProfileRequest $request): JsonResponse
    {

        // Iniciar a transação
        DB::beginTransaction();

        try {
            // Recuperar os dados do usuário logado
            $user = User::where('id', Auth::id())->first();

            // Acessar o IF quando não encontrar o usuário
            if (!$user) {

                // Salvar log
                Log::notice('Perfil não encontrado.');

                return response()->json([
                    'status' => false,
                    'message' => 'Perfil não encontrado!'
                ], 400);
            }

            // Editar as informações do registro no banco de dados
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Operação é concluída com êxito
            DB::commit();

            // Salvar log
            Log::info('Perfil editado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Retornar os dados em formato de objeto e status 200
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Perfil editado com sucesso!',
            ], 200);
        } catch (Exception $e) {

            // Operação não é concluída com êxito
            DB::rollBack();

            // Salvar log
            Log::notice('Perfil não editado.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retorna uma mensagem de erro com status 400
            return response()->json([
                'status' => true,
                'message' => 'Perfil não editado!'
            ], 400);
        }
    }
}
