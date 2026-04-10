<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>TP Ticketing</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body style="background-color: #eaf2ff;">

<header class="main-header"
        style="position: fixed; top: 0; left: 0; right: 0; background-color: #2c7be5; z-index: 1000;">
    <div class="header-content">

        <!-- Logo -->
        <div class="logo">
            <a href="/accueil">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo">
            </a>
        </div>

        <!-- Menu -->
        <nav class="main-nav">
            <a href="/accueil">Accueil</a>
            @if(auth()->user()?->role === 'client')
                <a href="{{ route('client.projects.index') }}">Mes projets</a>
                <a href="{{ route('client.tickets.index') }}">Tickets facturables</a>
            @elseif(auth()->user()?->role === 'collaborateur')
                <a href="/projects">Projets assignés</a>
                <a href="/tickets">Mes tickets</a>
                <a href="/tickets/create">Créer un ticket</a>
            @else
                <a href="/dashboard">Dashboard</a>
                <a href="/projects">Projets</a>
                <a href="/tickets">Tickets</a>
                <a href="/clients">Clients</a>
                <a href="/tickets/create">Créer un ticket</a>
                <a href="/projects/create">Créer un projet</a>
                <a href="/users">Utilisateurs</a>
            @endif

            <a href="{{ route('settings.edit') }}">Paramètres</a>

            @php
                $currentRole = auth()->user()?->role ?? 'inconnu';
                $roleLabel = match ($currentRole) {
                    'admin' => 'Administrateur',
                    'collaborateur' => 'Collaborateur',
                    'client' => 'Client',
                    default => ucfirst($currentRole),
                };
            @endphp

            <span class="nav-user-badge">{{ auth()->user()?->name ?? 'Utilisateur' }}</span>
            <span class="nav-role-badge nav-role-badge-{{ $currentRole }}">{{ $roleLabel }}</span>

            <form method="POST" action="/logout" class="nav-logout-form">
                @csrf
                <button type="submit" class="nav-logout-button">Déconnexion</button>
            </form>
        </nav>

    </div>
</header>

<main style="
    min-height: calc(100vh - 80px);
    display: flex;
    margin-top: 80px;
">

    @yield('content')

</main>

</body>
</html>