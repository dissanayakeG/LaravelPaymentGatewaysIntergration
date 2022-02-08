<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//paypal
Route::post('create-payment', [\App\Http\Controllers\PaypalController::class, 'creatPayment']);
Route::post('execute-payment',[\App\Http\Controllers\PaypalController::class, 'executePayment']);

//transferwise
Route::post('tw-test',[\App\Http\Controllers\TWController::class, 'index']);
