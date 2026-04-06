<?php

use Illuminate\Support\Facades\Route;
use App\Models\Client;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Routes principales
|--------------------------------------------------------------------------
*/

// Page de garde
Route::get('/', function () {
    return view('landing', [
        'isAuthenticated' => auth()->check(),
        'userName' => auth()->user()?->name,
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
            'openTickets' => Ticket::whereIn('status', ['Nouveau', 'En cours', 'ouvert'])->count(),
            'totalClients' => Client::count(),
        ];
    } catch (\Throwable $exception) {
        // L'accueil doit rester accessible meme si la base n'est pas disponible.
    }

    return view('home', $stats);
})->middleware('auth')->name('home');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');

// Clients
Route::resource('clients', ClientController::class)->middleware(['auth', 'role:admin,collaborateur']);

/*
|--------------------------------------------------------------------------
| Projects (CRUD)
|--------------------------------------------------------------------------
*/
Route::resource('projects', ProjectController::class)->middleware(['auth', 'role:admin,collaborateur']);

/*
|--------------------------------------------------------------------------
| Tickets (CRUD)
|--------------------------------------------------------------------------
*/

Route::resource('tickets', TicketController::class)->middleware(['auth', 'role:admin,collaborateur']);

/*
|--------------------------------------------------------------------------
| Users (Admin)
|--------------------------------------------------------------------------
*/
Route::resource('users', UserController::class)
    ->only(['index', 'update'])
    ->middleware(['auth', 'role:admin']);