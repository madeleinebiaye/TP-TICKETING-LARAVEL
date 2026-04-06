@extends('layouts.app')

@section('content')

<main class="dashboard" style="margin-top: 100px; width: 100%;">

    <div class="dashboard-title-wrapper">
        <h1 class="dashboard-title">
            Tableau de bord de performance
        </h1>
    </div>

    <!-- KPI -->
    <section class="kpi-grid">

        <div class="kpi-card">
            <span>Total tickets</span>
            <div style="font-size:2rem;font-weight:bold;">
                {{ $totalTickets }}
            </div>
        </div>

        <div class="kpi-card">
            <span>Total projets</span>
            <div style="font-size:2rem;font-weight:bold;">
                {{ $totalProjects }}
            </div>
        </div>

        <div class="kpi-card">
            <span>Projets créés ce mois</span>
            <div style="font-size:2rem;font-weight:bold;">
                {{ $projectsThisMonth }}
            </div>
        </div>

        <div class="kpi-card">
            <span>Projets actifs</span>
            <div style="font-size:2rem;font-weight:bold;">
                {{ $activeProjects }}
            </div>
        </div>

    </section>

    <section class="dashboard-row">

        <!-- Bloc gauche -->
        <div class="panel highlight">
            <h3>Projets créés</h3>
            <p class="big-number">{{ $totalProjects }}</p>
        </div>

        <!-- Bloc centre -->
        <div class="panel">
            <h3>Statut des tickets</h3>

            <div class="status-row">
                <span>Nouveaux</span>
                <div class="bar">
                    <div class="bar-fill green"
                         style="width: {{ $totalTickets > 0 ? ($nbNouveau / $totalTickets) * 100 : 0 }}%"></div>
                </div>
                <span>{{ $nbNouveau }}</span>
            </div>

            <div class="status-row">
                <span>En cours</span>
                <div class="bar">
                    <div class="bar-fill blue"
                         style="width: {{ $totalTickets > 0 ? ($nbEnCours / $totalTickets) * 100 : 0 }}%"></div>
                </div>
                <span>{{ $nbEnCours }}</span>
            </div>

        </div>

        <!-- Bloc droite -->
        <div class="panel">
            <h3>Résumé projets</h3>
            <ul class="top-list">
                <li>Total projets <span>{{ $totalProjects }}</span></li>
                <li>Projets actifs <span>{{ $activeProjects }}</span></li>
                <li>Sans ticket <span>{{ $projectsWithoutTickets }}</span></li>
                <li>Créés ce mois <span>{{ $projectsThisMonth }}</span></li>
            </ul>
        </div>

    </section>

</main>

@endsection