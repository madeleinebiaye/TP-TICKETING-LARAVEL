<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

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
Route::resource('clients', ClientController::class);

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