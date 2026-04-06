<?php

use Illuminate\Support\Facades\Route;
use App\Models\Client;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;
use App\Models\Project;
use App\Models\Ticket;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot-password');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);

/*
|--------------------------------------------------------------------------
| Routes principales
|--------------------------------------------------------------------------
*/

// Page de garde
Route::get('/', function () {
    return view('landing', [
        'isAuthenticated' => session()->has('user_id'),
        'userName' => session('user_name'),
    ]);
})->name('landing');

// Accueil applicatif
Route::get('/accueil', function () {
    $stats = [
        'totalProjects' => 0,
        'totalTickets' => 0,
        'openTickets' => 0,
        'totalClients' => 0,
    ];

    try {
        $stats = [
            'totalProjects' => Project::count(),
            'totalTickets' => Ticket::count(),
            'openTickets' => Ticket::where('status', 'En cours')->count(),
            'totalClients' => Client::count(),
        ];
    } catch (\Throwable $exception) {
        // L'accueil doit rester accessible meme si la base n'est pas disponible.
    }

    return view('home', $stats);
})->middleware('session.auth')->name('home');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('session.auth');

// Clients
Route::resource('clients', ClientController::class)->middleware('session.auth');

/*
|--------------------------------------------------------------------------
| Projects (CRUD)
|--------------------------------------------------------------------------
*/
Route::resource('projects', ProjectController::class)->middleware('session.auth');

/*
|--------------------------------------------------------------------------
| Tickets (CRUD)
|--------------------------------------------------------------------------
*/

Route::resource('tickets', TicketController::class)->middleware('session.auth');