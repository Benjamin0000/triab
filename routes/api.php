<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PosController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('/get-data/{shop_id}/{id?}', [PosController::class, 'get_data']);
Route::post('/save-order', [PosController::class, 'save_order']);
Route::get('/check-auth/{shop_id}', [PosController::class, 'check_auth']);
