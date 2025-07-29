<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function index(): JsonResponse
    {
        //Recupera os dados  e retorna com paginação
        $users = User::orderby('id','desc')->paginate(40);

        //Retorna os dados como uma resposta JSON
        return response()->json([
            'status' => 'success',
            'users' => $users
        ],200);
    }

    public function show(User $id) : JsonResponse
    {
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

            //Retorna os dados como uma resposta JSON com status 201
            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => 'created successfully'
            ],201);
        } catch (\Throwable $th) {

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

        return response()->json([
            'status' => true,
            'user' => $id,
            'message' => 'updated successfully'
        ],200);
      } catch (\Throwable $th) {

        //Cancela a transação em caso de erro
        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ],500);
      }
    }


    function destroy(User $id) : JsonResponse
    {
       try {
        $id->delete();
        return response()->json([
            'status' => true,
            'user' => $id,
            'message' => 'deleted successfully'
        ],200);
       } catch (\Throwable $th) {

        DB::rollBack();
        return response()->json([
            'status' => false,
            'message' => $th->getMessage()
        ],400);
       }
    }
}