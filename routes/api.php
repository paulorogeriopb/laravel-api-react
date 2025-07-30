<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BillController;


//Rota pública
Route::post('/login', [LoginController::class, 'login'])->name('login');

//Rota privada
Route::group(['middleware' => ['auth:sanctum']], function () {

//users
Route::get('/users',[UserController::class, 'index']); //http://0.0.0.0:8695/api/users?page=1
Route::get('/users/{id}',[UserController::class, 'show']); //http://0.0.0.0:8695/api/users/1
Route::post('/users',[UserController::class, 'store']); //http://0.0.0.0:8695/api/users
Route::put('/users/{id}',[UserController::class, 'update']); //http://0.0.0.0:8695/api/users/1
Route::delete('/users/{id}',[UserController::class, 'destroy']); //http://0.0.0.0:8695/api/users/1


//bills
Route::get('/bills',[BillController::class, 'index']); //http://0.0.0.0:8695/api/bills?page=1
Route::get('/bills/{id}',[BillController::class, 'show']); //http://0.0.0.0:8695/api/bills/1
Route::post('/bills',[BillController::class, 'store']); //http://0.0.0.0:8695/api/bills
Route::put('/bills/{id}',[BillController::class, 'update']); //http://0.0.0.0:8695/api/bills/1
Route::delete('/bills/{id}',[BillController::class, 'destroy']); //http://0.0.0.0:8695/api/bills/1



Route::post('/logout', [LoginController::class, 'logout'])->name('logout');//http://0.0.0.0:8695/api/logout


});
