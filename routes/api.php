<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//to do (auth)
Route::middleware('guest')->group(function (){
    Route::post('create-user',[\App\Http\Controllers\UserController::class, 'store']);
    Route::post('delete-user/{id}',[\App\Http\Controllers\UserController::class, 'delete']);//to do
    Route::post('login',[\App\Http\Controllers\AuthorizationController::class, 'login']);
    Route::post('delete-employee/{id}',[\App\Http\Controllers\EmployeeController::class, 'delete']);
    Route::get('employees', [\App\Http\Controllers\EmployeeController::class, 'getAll']);
});

Route::middleware('auth:sanctum')->group(function (){
    Route::get('user', [\App\Http\Controllers\UserController::class, 'getAuthorizedUser']);
    Route::get('logout',[\App\Http\Controllers\AuthorizationController::class, 'logout']);
    Route::post('create-employee', [\App\Http\Controllers\EmployeeController::class, 'store']);
});
