<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

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

Route::delete("/réservation", [APIController::class,"delete"]);

Route::post("/réservation", [APIController::class,"create"]);

Route::get("/réservation", [APIController::class,"verify"]);

Route::get("/informations", [APIController::class,"data"]);