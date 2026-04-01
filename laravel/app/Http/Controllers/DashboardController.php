<?php

namespace App\Http\Controllers;

use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTickets = Ticket::count();

        $nbNouveau = Ticket::where('status', 'Nouveau')->count();
        $nbEnCours = Ticket::where('status', 'En cours')->count();
        $nbTermine = Ticket::where('status', 'Terminé')->count();

        return view('dashboard', compact(
            'totalTickets',
            'nbNouveau',
            'nbEnCours',
            'nbTermine'
        ));
    }
}