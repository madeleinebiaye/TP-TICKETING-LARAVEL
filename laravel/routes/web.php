<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;

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

// Projects (temporaire pour étape 5)
Route::get('/projects', function () {
    return view('projects');
});

/*
|--------------------------------------------------------------------------
| Tickets (CRUD complet avec resource)
|--------------------------------------------------------------------------
*/

Route::resource('tickets', TicketController::class);