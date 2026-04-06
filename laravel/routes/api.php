<?php

use App\Http\Controllers\Api\TicketApiController;
use Illuminate\Support\Facades\Route;

Route::get('/tickets', [TicketApiController::class, 'index']);
Route::post('/tickets', [TicketApiController::class, 'store']);
Route::get('/tickets/stats', [TicketApiController::class, 'stats']);