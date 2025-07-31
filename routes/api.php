<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RecoverPasswordCodeController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BillController;
use App\Http\Controllers\Api\ProfileController;



//Rota pública
Route::post('/login', [LoginController::class, 'login'])->name('login');//http://0.0.0.0:8695/api/login

//Recuperar senha
Route::post("/forgot-password-code", [RecoverPasswordCodeController::class, 'forgotPasswordCode']);
Route::post("/reset-password-validate-code", [RecoverPasswordCodeController::class, 'resetPasswordValidateCode']);
Route::post("/reset-password-code", [RecoverPasswordCodeController::class, 'resetPasswordCode']);



//Rota privada
Route::group(['middleware' => ['auth:sanctum']], function () {

    //Profile
    Route::get('profile',[ProfileController::class, 'show']);
    Route::put('profile',[ProfileController::class, 'update']);
    Route::put('/profile-password', [ProfileController::class, 'updatePassword']); // PUT - http://127.0.0.1:8000/api/profile-password

    //users
    Route::get('/users',[UserController::class, 'index']); //http://0.0.0.0:8695/api/users?page=1
    Route::get('/users/{id}',[UserController::class, 'show']); //http://0.0.0.0:8695/api/users/1
    Route::post('/users',[UserController::class, 'store']); //http://0.0.0.0:8695/api/users
    Route::put('/users/{id}',[UserController::class, 'update']); //http://0.0.0.0:8695/api/users/1
    Route::put('/users-password/{user}', [UserController::class, 'updatePassword']); // PUT - http://127.0.0.1:8000/api/users-password/1
    Route::delete('/users/{id}',[UserController::class, 'destroy']); //http://0.0.0.0:8695/api/users/1

    //bills
    Route::get('/bills',[BillController::class, 'index']); //http://0.0.0.0:8695/api/bills?page=1
    Route::get('/bills/{id}',[BillController::class, 'show']); //http://0.0.0.0:8695/api/bills/1
    Route::post('/bills',[BillController::class, 'store']); //http://0.0.0.0:8695/api/bills
    Route::put('/bills/{id}',[BillController::class, 'update']); //http://0.0.0.0:8695/api/bills/1
    Route::delete('/bills/{id}',[BillController::class, 'destroy']); //http://0.0.0.0:8695/api/bills/1

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');//http://0.0.0.0:8695/api/logout

});
