<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;

/*
|--------------------------------------------------------------------------
| Routes principales
|--------------------------------------------------------------------------
*/

// Accueil
Route::get('/', function () {
    return view('home');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);

// Clients
Route::get('/clients', function () {
    return view('clients');
});

/*
|--------------------------------------------------------------------------
| Projects (CRUD)
|--------------------------------------------------------------------------
*/

Route::resource('projects', ProjectController::class);

/*
|--------------------------------------------------------------------------
| Tickets (CRUD)
|--------------------------------------------------------------------------
*/

Route::resource('tickets', TicketController::class);