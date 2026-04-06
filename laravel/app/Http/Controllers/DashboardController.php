<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Project;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTickets = Ticket::count();
        $totalClients = Client::count();
        $clientsWithProjects = Client::has('projects')->count();
        $totalProjects = Project::count();
        $activeProjects = Project::has('tickets')->count();
        $projectsWithoutTickets = Project::doesntHave('tickets')->count();
        $projectsThisMonth = Project::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $latestProjects = Project::with('client')
            ->withCount('tickets')
            ->latest()
            ->take(5)
            ->get();
        $topClient = Client::withCount('projects')
            ->orderByDesc('projects_count')
            ->orderBy('name')
            ->first();

        $nbNouveau = Ticket::whereIn('status', ['Nouveau', 'ouvert'])->count();
        $nbEnCours = Ticket::where('status', 'En cours')->count();
        $nbTermine = Ticket::where('status', 'Terminé')->count();

        return view('dashboard', compact(
            'totalTickets',
            'totalClients',
            'clientsWithProjects',
            'totalProjects',
            'activeProjects',
            'projectsWithoutTickets',
            'projectsThisMonth',
            'latestProjects',
            'topClient',
            'nbNouveau',
            'nbEnCours',
            'nbTermine'
        ));
    }
}