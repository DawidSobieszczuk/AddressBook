<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\UserController;
use App\Models\Address;
use Illuminate\Http\Request;
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

Route::post('/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/user', [UserController::class, 'viewCurrentLogged']);

    Route::post('/addresses/search', [AddressController::class, 'search']);
    Route::get('/addresses', [AddressController::class, 'index']);
    Route::prefix('address')->group(function () {
        Route::post('/', [AddressController::class, 'store']);
        Route::put('/{id}', [AddressController::class, 'update']);
        Route::delete('/{id}', [AddressController::class, 'destroy']);
    });
});
