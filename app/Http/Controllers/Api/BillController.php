<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BillRequest;
use Illuminate\Http\Request;
use App\Models\Bill;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


class BillController extends Controller
{
    public function index(): JsonResponse
    {
        //Recupera os dados  e retorna com paginação
        $bills = Bill::orderby('id','desc')->paginate(40);

        //Retorna os dados como uma resposta JSON
        return response()->json([
            'status' => 'success',
            'bills' => $bills
        ],200);
    }

    public function show(Bill $id) : JsonResponse
    {
        //Retorna os dados como uma resposta JSON
        return response()->json([
            'status' => true,
            'bill' => $id
        ],200);
    }

    public function store(BillRequest $request ) : JsonResponse
    {

        //Inicia a transação
        DB::beginTransaction();

        try{
        $bill = Bill::create([
            'name' => $request->name,
            'bill_value' => "$request->bill_value",
            'due_date' => $request->due_date
            ]);

            //Confirma a transação
            DB::commit();

            //Retorna os dados como uma resposta JSON com status 201
            return response()->json([
                'status' => true,
                'bill' => $bill,
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

    public function update(BillRequest $request, Bill $id)
    {

      //Inicia a transação
      Db::beginTransaction();

      try{
       $id->update([
           'name' => $request->name,
           'bill_value' => $request->bill_value,
           'due_date' => $request->due_date
       ]);

         //Confirma a transação
        DB::commit();

        return response()->json([
            'status' => true,
            'bill' => $id,
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


    function destroy(Bill $id) : JsonResponse
    {
       try {
        $id->delete();
        return response()->json([
            'status' => true,
            'bill' => $id,
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
