<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserPasswordRequest;


class UserController extends Controller
{
    public function index(): JsonResponse
    {
        //Recupera os dados  e retorna com paginação
        $users = User::orderby('id','desc')->paginate(40);

        Log::info('Listar os Usuários', ['action_user_id' => Auth::id()]);

        //Retorna os dados como uma resposta JSON
        return response()->json([
            'status' => 'success',
            'users' => $users
        ],200);
    }

    public function show(User $id) : JsonResponse
    {
        Log::info('Visualizar o Usuário', ['user_id' => $id->id ,'action_user_id' => Auth::id()]);

        //Retorna os dados como uma resposta JSON
        return response()->json([
            'status' => true,
            'bill' => $id
        ],200);
    }

    public function store(UserRequest $request ) : JsonResponse
    {

        //Inicia a transação
        DB::beginTransaction();

        try{
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
            ]);

            //Confirma a transação
            DB::commit();

            Log::info('Cadastrar o Usuário', ['user_id' => $user->id ,'action_user_id' => Auth::id()]);

            //Retorna os dados como uma resposta JSON com status 201
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'created successfully'
            ],201);
        } catch (\Throwable $th) {

            Log::info('Usuário não Cadastrado', ['action_user_id' => Auth::id(), 'error' => $th->getMessage()]);

            //Cancela a transação em caso de erro
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ],500);
        }
    }

    public function update(UserRequest $request, User $id)
    {

      //Inicia a transação
      Db::beginTransaction();

      try{
       $id->update([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password)
       ]);

         //Confirma a transação
        DB::commit();

        Log::info('Editar o Usuário', ['user_id' => $id->id ,'action_user_id' => Auth::id()]);

        return response()->json([
            'status' => true,
            'user' => $id,
            'message' => 'updated successfully'
        ],200);
      } catch (\Throwable $th) {

        //Cancela a transação em caso de erro
        DB::rollBack();

        Log::info('Erro ao Editar o Usuário ', ['action_user_id' => Auth::id(), 'error' => $th->getMessage()]);


        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ],500);
      }
    }


    public function updatePassword(UserPasswordRequest $request, User $user): JsonResponse
    {

        // Iniciar a transação
        DB::beginTransaction();

        try {

            $user->update([
                'password' => $request->password,
            ]);

            // Operação é concluída com êxito
            DB::commit();

            // Salvar log
            Log::info('Senha do usuário editado.', ['user_id' => $user->id, 'action_user_id' => Auth::id()]);

            // Retornar os dados em formato de objeto e status 200
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'Senha do usuário editado com sucesso!',
            ], 200);
        } catch (\Throwable $th) {

            // Operação não é concluída com êxito
            DB::rollBack();

            // Salvar log
            Log::notice('Senha do usuário não editado.', ['action_user_id' => Auth::id(), 'error' => $e->getMessage()]);

            // Retornar os dados em formato de objeto e status 400
            return response()->json([
                'status' => false,
                'message' => 'Senha do usuário não editado!',
            ], 400);
        }
    }

    function destroy(User $id) : JsonResponse
    {
       try {
        $id->delete();

        Log::info('Excluir o Usuário', ['user_id' => $id->id ,'action_user_id' => Auth::id()]);

        return response()->json([
            'status' => true,
            'user' => $id,
            'message' => 'deleted successfully'
        ],200);
       } catch (\Throwable $th) {

        DB::rollBack();

        Log::info('Erro ao Excluir o Usuário ', ['action_user_id' => Auth::id(), 'error' => $th->getMessage()]);

        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ],400);
       }
    }



}
