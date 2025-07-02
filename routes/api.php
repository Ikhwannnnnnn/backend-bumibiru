<?php

use App\Http\Controllers\api\C_MapsAPI;
use App\Http\Controllers\TitikController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/maps', [C_MapsAPI::class, 'index']);
Route::get('/maps/search', [C_MapsAPI::class, 'search']);

//Routes ke data titik
Route::get('/titik', [TitikController::class, 'index']);


