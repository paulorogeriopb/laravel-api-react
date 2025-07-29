<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request) {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            //recuperar os dados so o usuário autenticado
            $user = Auth::user();

            //gerar token
            $token =  $request->user()->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'token' => $token,
                'user' => $user,
            ],201);

            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ],401);
            }
    }
}
