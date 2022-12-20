<?php

use App\Http\Controllers\CalculationController;
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

Route::group(['prefix' => '/calculations'], function () {
    Route::get('/', [CalculationController::class, 'index']);
    Route::post('/', [CalculationController::class, 'calculate']);
    Route::delete('/', [CalculationController::class, 'clear']);
    Route::delete('/{calculation}', [CalculationController::class, 'delete']);
});


