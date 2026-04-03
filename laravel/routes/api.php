<?php

use Illuminate\Support\Facades\Route;
use App\Models\Ticket;

Route::get('/tickets/stats', function () {

    return response()->json([
        'total' => \App\Models\Ticket::count(),
        'nouveau' => \App\Models\Ticket::where('status', 'like', '%Nouveau%')->count(),
        'encours' => \App\Models\Ticket::where('status', 'like', '%En cours%')->count(),
        'termine' => \App\Models\Ticket::where('status', 'like', '%Terminé%')->count()
    ]);

});