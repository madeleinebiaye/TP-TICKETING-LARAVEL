<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTickets = Ticket::count();
        $totalProjects = Project::count();
        $activeProjects = Project::has('tickets')->count();
        $projectsWithoutTickets = Project::doesntHave('tickets')->count();
        $projectsThisMonth = Project::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        $nbNouveau = Ticket::where('status', 'Nouveau')->count();
        $nbEnCours = Ticket::where('status', 'En cours')->count();
        $nbTermine = Ticket::where('status', 'Terminé')->count();

        return view('dashboard', compact(
            'totalTickets',
            'totalProjects',
            'activeProjects',
            'projectsWithoutTickets',
            'projectsThisMonth',
            'nbNouveau',
            'nbEnCours',
            'nbTermine'
        ));
    }
}