<?php

use Illuminate\Support\Facades\Route;
use App\Models\Client;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientValidationController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\TimeEntryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\Project;
use App\Models\Ticket;

/*
|--------------------------------------------------------------------------
| Authentification
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
    $user = auth()->user();

    $stats = [
        'totalProjects' => 0,
        'totalTickets' => 0,
        'openTickets' => 0,
        'totalClients' => 0,
    ];

    try {
        if ($user?->role === 'client') {
            $client = $user->clientProfile;

            if ($client) {
                $stats = [
                    'totalProjects' => Project::where('client_id', $client->id)->count(),
                    'totalTickets' => Ticket::whereHas('project', fn ($query) => $query->where('client_id', $client->id))->count(),
                    'openTickets' => Ticket::whereIn('status', ['Nouveau', 'En cours', 'ouvert'])
                        ->whereHas('project', fn ($query) => $query->where('client_id', $client->id))
                        ->count(),
                    'totalClients' => 1,
                ];
            }
        } else {
            $stats = [
                'totalProjects' => Project::count(),
                'totalTickets' => Ticket::count(),
                'openTickets' => Ticket::whereIn('status', ['Nouveau', 'En cours', 'ouvert'])->count(),
                'totalClients' => Client::count(),
            ];
        }
    } catch (\Throwable $exception) {
        // L'accueil doit rester accessible meme si la base n'est pas disponible.
    }

    return view('home', $stats);
})->middleware('auth')->name('home');

// Tableau de bord
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'role:admin,collaborateur']);

// Clients
Route::resource('clients', ClientController::class)->middleware(['auth', 'role:admin,collaborateur']);

/*
|--------------------------------------------------------------------------
| Projets (CRUD)
|--------------------------------------------------------------------------
*/
Route::resource('projects', ProjectController::class)->middleware(['auth', 'role:admin,collaborateur']);

Route::get('/projects/{project}/contract/edit', [ContractController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('contracts.edit');

Route::put('/projects/{project}/contract', [ContractController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('contracts.update');

/*
|--------------------------------------------------------------------------
| Tickets (CRUD)
|--------------------------------------------------------------------------
*/

Route::resource('tickets', TicketController::class)->middleware(['auth', 'role:admin,collaborateur']);

Route::post('/tickets/{ticket}/time-entries', [TimeEntryController::class, 'store'])
    ->middleware(['auth', 'role:admin,collaborateur'])
    ->name('time-entries.store');

Route::delete('/time-entries/{timeEntry}', [TimeEntryController::class, 'destroy'])
    ->middleware(['auth', 'role:admin,collaborateur'])
    ->name('time-entries.destroy');

Route::get('/client/tickets', [ClientValidationController::class, 'index'])
    ->middleware(['auth', 'role:client'])
    ->name('client.tickets.index');

Route::get('/client/projects', [ClientValidationController::class, 'projects'])
    ->middleware(['auth', 'role:client'])
    ->name('client.projects.index');

Route::patch('/client/tickets/{ticket}/validate', [ClientValidationController::class, 'validateTicket'])
    ->middleware(['auth', 'role:client'])
    ->name('client.tickets.validate');

Route::patch('/client/tickets/{ticket}/refuse', [ClientValidationController::class, 'refuseTicket'])
    ->middleware(['auth', 'role:client'])
    ->name('client.tickets.refuse');

/*
|--------------------------------------------------------------------------
| Utilisateurs (Admin)
|--------------------------------------------------------------------------
*/
Route::resource('users', UserController::class)
    ->only(['index', 'update'])
    ->middleware(['auth', 'role:admin']);