<?php

use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});


Route::get('/clients', function () {
    return view('clients');
});

Route::get('/projects', function () {
    return view('projects');
});

use App\Http\Controllers\TicketController;

Route::resource('tickets', TicketController::class);
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index']);