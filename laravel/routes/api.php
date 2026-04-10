<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\Api\TicketApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthApiController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Routes API (protégées par Sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |----------------------------------------------------------------------
    | Authentification
    |----------------------------------------------------------------------
    */
    Route::get('/me', [AuthApiController::class, 'me']);
    Route::post('/logout', [AuthApiController::class, 'logout']);

    /*
    |----------------------------------------------------------------------
    | Tickets
    |----------------------------------------------------------------------
    */
    Route::get('/tickets/stats', [TicketApiController::class, 'stats']);
    Route::get('/tickets', [TicketApiController::class, 'index']);
    Route::post('/tickets', [TicketApiController::class, 'store']);
    Route::get('/tickets/{id}', [TicketApiController::class, 'show']);
    Route::put('/tickets/{id}', [TicketApiController::class, 'update']);
    Route::delete('/tickets/{id}', [TicketApiController::class, 'destroy']);

    /*
    |----------------------------------------------------------------------
    | Projets
    |----------------------------------------------------------------------
    */
    Route::get('/projects', [ProjectApiController::class, 'index']);
    Route::post('/projects', [ProjectApiController::class, 'store']);
    Route::get('/projects/{id}', [ProjectApiController::class, 'show']);
    Route::put('/projects/{id}', [ProjectApiController::class, 'update']);
    Route::delete('/projects/{id}', [ProjectApiController::class, 'destroy']);

    /*
    |----------------------------------------------------------------------
    | Clients
    |----------------------------------------------------------------------
    */
    Route::get('/clients', [ClientApiController::class, 'index']);
    Route::post('/clients', [ClientApiController::class, 'store']);
    Route::get('/clients/{id}', [ClientApiController::class, 'show']);
    Route::put('/clients/{id}', [ClientApiController::class, 'update']);
    Route::delete('/clients/{id}', [ClientApiController::class, 'destroy']);
});